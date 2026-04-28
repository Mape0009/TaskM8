<!DOCTYPE html>
<html lang="da" class="no-js">
<head>
    @php
        \Carbon\Carbon::setLocale('da');
        $pageTitle = 'TaskM8 | Vagter for ' . $task->taskName;
        $metaDescription = 'Administrer vagter for opgaven ' . $task->taskName . '.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vagter for {{ $task->taskName }} | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('css/shifts-index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
</head>
<body class="shifts-simple-page">
    @include('partials.header', ['currentPage' => 'tasks'])

    <main class="main-content-full">
        @php
            $currentUser = auth()->user();
            $currentUserRole = null;
            if ($currentUser) {
                $p = \App\Models\EventParticipant::where('eventId', $task->eventId)
                    ->where('userId', $currentUser->id)
                    ->first();
                $currentUserRole = $p?->eventRole;
            }
            $shiftsCount = $task->shifts->count();
            $uniqueUsers = $task->shifts->pluck('userId')->unique()->count();
        @endphp
        <div class="overview-shell shifts-simple-shell">
            <section class="shifts-page-header">
                <div class="shifts-page-header-copy">
                    <h1>Vagtplan for {{ $task->taskName }}</h1>
                    <p>En enkel oversigt over alle vagter for opgaven. Hver vagt vises på én linje.</p>
                    <p class="shifts-page-meta">{{ $shiftsCount }} vagter · {{ $uniqueUsers }} personer</p>
                </div>
                <div class="shifts-page-actions">
                    @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'create-shift'))
                        <a href="{{ route('tasks.shifts.create', $task->id) }}" class="btn primary-btn">Opret vagt</a>
                    @endif
                    <a href="{{ route('events.tasks.index', $task->eventId) }}" class="btn secondary-btn">Tilbage til opgaver</a>
                </div>
            </section>

            <div class="edit-container shifts-simple-container">
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if(session('success'))
                <div class="alert alert-success">
                    <i class="fas fa-check-circle"></i>
                    {{ session('success') }}
                </div>
            @endif

            @if($task->shifts->count() > 0)
                @php
                    $currentUser = auth()->user();
                    $filter = request()->query('filter', 'all');
                    $isMine = $filter === 'mine';
                    $displayShifts = $task->shifts;
                    if ($isMine && $currentUser) {
                        $displayShifts = $displayShifts->where('userId', $currentUser->id);
                    }
                @endphp
                <div class="shifts-toolbar">
                    <div class="shifts-filter" role="group" aria-label="Filtrer vagter">
                        <span class="shifts-filter-label">Vis:</span>
                        <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn {{ $isMine ? 'secondary-btn' : 'primary-btn' }}">Alle vagter</a>
                        <a href="{{ route('tasks.shifts.index', $task->id) }}?filter=mine" class="btn {{ $isMine ? 'primary-btn' : 'secondary-btn' }}">Mine vagter</a>
                    </div>
                    <label class="shifts-search-wrap" for="shift-search">
                        <i class="fas fa-search" aria-hidden="true"></i>
                        <input id="shift-search" type="search" class="shifts-search-input" placeholder="Søg efter navn eller e-mail" aria-label="Søg i vagtlisten">
                    </label>
                </div>

                <div class="shifts-simple-table-scroll">
                <div class="shifts-simple-table" role="table" aria-label="Vagtliste">
                    <div class="shifts-line shifts-line-head" role="row">
                        <div class="line-col" role="columnheader">Person</div>
                        <div class="line-col" role="columnheader">Tid</div>
                        <div class="line-col line-col-actions" role="columnheader">Handlinger</div>
                    </div>

                    <div class="shifts-simple-body">
                        @forelse($displayShifts as $shift)
                            @php
                                $startTime = \Carbon\Carbon::parse($shift->startTime);
                                $endTime = \Carbon\Carbon::parse($shift->endTime);
                                $sameDay = $startTime->isSameDay($endTime);
                                $timeRangeText = $sameDay
                                    ? ($startTime->translatedFormat('j F Y') . ' kl. ' . $startTime->format('H:i') . '  -  ' . $endTime->format('H:i'))
                                    : ($startTime->translatedFormat('j F Y H:i') . '  -  ' . $endTime->translatedFormat('j F Y H:i'));
                                $isVolunteerRequest = $shift->status === 'pending' && $shift->userId && $shift->created_at && $shift->updated_at && !$shift->created_at->equalTo($shift->updated_at);
                            @endphp
                            <div class="shifts-line" role="row" data-shift-user="{{ strtolower(($shift->user?->name ?? $shift->user?->email ?? 'Ingen bruger') . ' ' . ($shift->user?->email ?? '')) }}">
                                <div class="line-col line-col-person" role="cell">
                                    <strong>{{ $shift->user?->name ?? $shift->user?->email ?? 'Ingen bruger' }}</strong>
                                    <span class="line-email">{{ $shift->user?->email ?? 'Ingen e-mail' }}</span>
                                    @if($isVolunteerRequest)
                                        <span class="shift-status-badge shift-status-pending">Afventer</span>
                                    @endif
                                </div>
                                <div class="line-col line-col-time" role="cell">{{ $timeRangeText }}</div>
                                <div class="line-col line-col-actions" role="cell">
                                    @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'volunteer-shift') && is_null($shift->userId))
                                        <form action="{{ route('tasks.shifts.volunteer', [$task->id, $shift->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn primary-btn" aria-label="Melder sig på vagt">Meld mig</button>
                                        </form>
                                    @endif
                                    @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'edit-shift') && $isVolunteerRequest)
                                        <form action="{{ route('tasks.shifts.accept', [$task->id, $shift->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn primary-btn" aria-label="Godkend frivillig">Godkend</button>
                                        </form>
                                        <form action="{{ route('tasks.shifts.deny', [$task->id, $shift->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            <button type="submit" class="btn danger-btn" aria-label="Afvis frivillig">Afvis</button>
                                        </form>
                                    @endif
                                    @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'edit-shift'))
                                        <a href="{{ route('tasks.shifts.edit', [$task->id, $shift->id]) }}" class="btn secondary-btn">Rediger</a>
                                    @endif
                                    @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'delete-shift'))
                                        <button type="button" class="btn danger-btn" aria-label="Slet vagt" onclick="openDeleteShiftModal({{ $shift->id }})">Slet</button>
                                    @endif
                                </div>
                            </div>

                            <div id="delete-shift-modal-{{ $shift->id }}" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="delete-shift-title-{{ $shift->id }}" style="display:none;">
                                <div class="confirm-modal-content">
                                    <div class="confirm-modal-body">
                                        <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                                            <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
                                        </svg>
                                        <h2 id="delete-shift-title-{{ $shift->id }}" class="confirm-title">Er du sikker?</h2>
                                        <p class="confirm-text">Vil du slette denne vagt? Dette kan ikke fortrydes.</p>
                                    </div>
                                    <div class="confirm-actions">
                                        <button type="button" class="confirm-btn cancel" onclick="closeDeleteShiftModal({{ $shift->id }})">Annuller</button>
                                        <form class="delete-shift-form" action="{{ route('tasks.shifts.destroy', [$task->id, $shift->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="confirm-btn danger">Slet</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <p class="empty-state shifts-empty">Ingen vagter fundet.</p>
                        @endforelse
                    </div>
                </div>
                </div>
            @else
                <div class="empty-state shifts-empty-state">
                    <h2>Ingen vagter endnu</h2>
                    <p>Opret den første vagt for at begynde at planlægge opgaven.</p>
                    @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'create-shift'))
                        <a href="{{ route('tasks.shifts.create', $task->id) }}" class="btn primary-btn">Opret første vagt</a>
                    @endif
                </div>
            @endif
        </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        function openDeleteShiftModal(id){
            var el = document.getElementById('delete-shift-modal-' + id);
            if(el){ el.style.display = 'flex'; }
        }
        function closeDeleteShiftModal(id){
            var el = document.getElementById('delete-shift-modal-' + id);
            if(el){ el.style.display = 'none'; }
        }

        window.addEventListener('click', function (event) {
            if (!event.target || !event.target.classList) return;
            if (event.target.classList.contains('confirm-modal')) {
                event.target.style.display = 'none';
            }
        });

        // Dark Mode Toggle
        document.addEventListener('DOMContentLoaded', function() {
            const darkModeToggle = document.getElementById('darkModeToggle');
            const html = document.documentElement;
            if (darkModeToggle) {
                darkModeToggle.addEventListener('click', function() {
                    html.classList.toggle('dark');
                    const isDark = html.classList.contains('dark');
                    localStorage.setItem('darkMode', isDark);
                });
            }
        });

        // Search in shifts list
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('shift-search');
            const rows = Array.from(document.querySelectorAll('.shifts-line[data-shift-user]'));
            if (!input || rows.length === 0) return;

            input.addEventListener('input', function() {
                const term = input.value.trim().toLowerCase();
                rows.forEach((row) => {
                    const haystack = row.getAttribute('data-shift-user') || '';
                    row.style.display = haystack.includes(term) ? '' : 'none';
                });
            });
        });

        // Loading state for delete buttons
        document.querySelectorAll('form.delete-shift-form').forEach(form => {
            form.addEventListener('submit', function() {
                const button = this.querySelector('button[type="submit"]');
                if (button) {
                    button.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Sletter...';
                    button.disabled = true;
                }
            });
        });
    </script>
</body>
</html>

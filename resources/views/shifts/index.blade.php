<!DOCTYPE html>
<html lang="da" class="no-js">
<head>
    @php
        $pageTitle = 'Vagter for ' . $task->taskName . ' | TaskM8';
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
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shifts-index.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <script src="https://unpkg.com/@formkit/auto-animate@latest/index.js"></script>
    <script>
        if (localStorage.getItem('darkMode') === 'true' || (!localStorage.getItem('darkMode') && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
            document.documentElement.classList.add('dark-mode');
        }
    </script>
</head>
<body>
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
        <div class="overview-shell">
            <section class="overview-hero">
                <div class="hero-copy">
                    <p class="eyebrow">Vagter</p>
                    <h1>Planlæg vagter for {{ $task->taskName }}</h1>
                    <p class="lede">
                        Se bemandingen, redigér tider og opret nye vagter for opgaven. Brug genvejene til hurtigt at vende tilbage til opgaverne.
                    </p>
                    <div class="hero-meta">
                        <span class="pill">Vagter: {{ $shiftsCount }}</span>
                        <span class="pill">Personer: {{ $uniqueUsers }}</span>
                        <span class="pill">Opgave: {{ $task->taskName }}</span>
                    </div>
                </div>
                <div class="hero-actions">
                    @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'create-shift'))
                        <a href="{{ route('tasks.shifts.create', $task->id) }}" class="btn create-btn">Opret vagt</a>
                    @endif
                    <a href="{{ route('events.tasks.index', $task->eventId) }}" class="btn secondary-ghost">Tilbage til opgaver</a>
                </div>
            </section>

            <div class="edit-container">
            @if(session('error'))
                <div class="alert alert-error">
                    <i class="fas fa-exclamation-triangle"></i>
                    {{ session('error') }}
                </div>
            @endif

            @if($task->shifts->count() > 0)
                <!-- Stats Cards -->
                <div class="stats-grid">
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-users"></i>
                        </div>
                        <div class="stat-content">
                            <h3>{{ $task->shifts->count() }}</h3>
                            <p>{{ $task->shifts->count() === 1 ? 'Vagt' : 'Vagter' }}</p>
                        </div>
                    </div>
                  
                    <div class="stat-card">
                        <div class="stat-icon">
                            <i class="fas fa-user-check"></i>
                        </div>
                        <div class="stat-content">
                            @php
                                $uniqueUsers = $task->shifts->pluck('userId')->unique()->count();
                            @endphp
                            <h3>{{ $uniqueUsers }}</h3>
                            <p>{{ $uniqueUsers === 1 ? 'Person' : 'Personer' }}</p>
                        </div>
                    </div>
                </div>

                <!-- Filter and Shifts List -->
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
                        <input id="shift-search" type="search" class="shifts-search-input" placeholder="Søg efter navn eller e-mail">
                    </label>
                </div>
                <div class="shifts-section">
                    <h2 class="shifts-title">
                        <i class="fas fa-list"></i>
                        {{ $isMine ? 'Mine Vagter' : 'Alle Vagter' }}
                    </h2>
                    <div class="shifts-list">
                        @forelse($displayShifts as $shift)
                            @php
                                $startTime = \Carbon\Carbon::parse($shift->startTime);
                                $endTime = \Carbon\Carbon::parse($shift->endTime);
                                $durationMinutes = $startTime->diffInMinutes($endTime);
                                $durationHours = intdiv($durationMinutes, 60);
                                $remainingMinutes = $durationMinutes % 60;
                                $durationText = $durationHours > 0
                                    ? ($durationHours . ' t' . ($remainingMinutes > 0 ? ' ' . $remainingMinutes . ' min' : ''))
                                    : ($remainingMinutes . ' min');
                            @endphp
                            <div class="shift-card">
                                <div class="shift-header">
                                    <div class="shift-user" data-shift-user="{{ strtolower($shift->user->name . ' ' . $shift->user->email) }}">
                                        <div class="user-avatar">
                                            <i class="fas fa-user"></i>
                                        </div>
                                        <div class="user-info">
                                            <h3>{{ $shift->user->name }}</h3>
                                            <p>{{ $shift->user->email }}</p>
                                        </div>
                                    </div>
                                    <div class="shift-actions">
                                        @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'edit-shift'))
                                        <a href="{{ route('tasks.shifts.edit', [$task->id, $shift->id]) }}" class="btn primary-btn">
                                            <i class="fas fa-edit"></i>
                                            Rediger
                                        </a>
                                        @endif
                                        @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'delete-shift'))
                                        <button type="button" class="btn danger-btn" aria-label="Slet vagt" onclick="openDeleteShiftModal({{ $shift->id }})">
                                            <i class="fas fa-trash"></i>
                                            Slet
                                        </button>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="shift-details">
                                    <div class="time-info">
                                        <div class="time-item">
                                            <div>
                                                <span class="time-label">Start</span>
                                                <span class="time-value">{{ $startTime->format('j.n.Y H:i') }}</span>
                                            </div>
                                        </div>
                                        <div class="time-separator">til</div>
                                        <div class="time-item">
                                            <div>
                                                <span class="time-label">Slut</span>
                                                <span class="time-value">{{ $endTime->format('j.n.Y H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <span class="duration-info">
                                        <i class="fas fa-clock" aria-hidden="true"></i>
                                        {{ $durationText }}
                                    </span>
                                </div>
                            </div>

                            <!-- Delete Shift Confirmation Modal -->
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
                                        <form action="{{ route('tasks.shifts.destroy', [$task->id, $shift->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="confirm-btn danger">Slet</button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="empty-state shifts-empty">
                                Ingen vagter fundet.
                            </div>
                        @endforelse
                    </div>
                </div>
            @else
                <!-- Empty State -->
                <div class="empty-state">
                    <div class="empty-icon">
                        <i class="fas fa-user-clock"></i>
                    </div>
                    <h2>Ingen vagter endnu</h2>
                    <p>Opret den første vagt for at begynde at planlægge opgaven.</p>
                    <a href="{{ route('tasks.shifts.create', $task->id) }}" class="btn primary-btn">
                        <i class="fas fa-plus"></i>
                        Opret Første Vagt
                    </a>
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

        // Auto-animate for smooth transitions
        document.addEventListener('DOMContentLoaded', function() {
            if (typeof autoAnimate !== 'undefined') {
                autoAnimate(document.querySelector('.shifts-list'));
                autoAnimate(document.querySelector('.stats-grid'));
            }
        });

        // Search in shifts list
        document.addEventListener('DOMContentLoaded', function() {
            const input = document.getElementById('shift-search');
            const cards = Array.from(document.querySelectorAll('.shift-card'));
            if (!input || cards.length === 0) return;

            input.addEventListener('input', function() {
                const term = input.value.trim().toLowerCase();
                cards.forEach((card) => {
                    const userEl = card.querySelector('[data-shift-user]');
                    const haystack = userEl ? userEl.getAttribute('data-shift-user') : '';
                    card.style.display = haystack.includes(term) ? '' : 'none';
                });
            });
        });

        // Loading state for delete buttons
        document.querySelectorAll('form').forEach(form => {
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

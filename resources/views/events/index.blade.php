<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Begivenheder | TaskM8';
        $metaDescription = 'Se dine begivenheder og få hurtigt overblik i TaskM8.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/participants-modal.css') }}">
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])

    <main class="main-content-full">
        <div class="overview-shell">
            @php
                $activeEventsCount = $events->count();
                $myEventsCount = (auth()->check() && isset($participant))
                    ? collect($participant)->where('userId', auth()->id())->unique('eventId')->count()
                    : 0;
            @endphp
            <section class="overview-hero">
                <div class="hero-copy">
                    <p class="eyebrow">Begivenheder</p>
                    <h1>Få overblik over dine begivenheder</h1>
                    <p class="lede">
                        Se dine kommende aktiviteter, håndter deltagere og spring direkte ind i opgaverne. Du kan altid åbne detaljer eller invitere flere deltagere.
                    </p>
                    <div class="hero-meta">
                        <span class="pill">Aktive begivenheder: {{ $activeEventsCount }}</span>
                        @auth
                            <span class="pill">Jeg deltager i: {{ $myEventsCount }}</span>
                        @else
                            <span class="pill pill-muted">Log ind for at se dine begivenheder</span>
                        @endauth
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ url('/dashboard?open=create') }}" class="btn create-btn">Opret begivenhed</a>
                    <a href="{{ url('/previousEvents') }}" class="btn secondary-ghost">Se tidligere</a>
                </div>
            </section>

            <section class="event-listing">
                <h2>Mine begivenheder</h2>
                <div class="event-list">
                    @forelse($events as $event)
                        <div class="event-card">
                            <div class="event-header">
                                <h3>{{ $event->eventName }}</h3>
                                @php
                                    $currentUserRole = null;
                                    $isOwnerMenu = false;
                                    if (auth()->check()) {
                                        foreach ($participant as $p) {
                                            if ($p->userId === auth()->id() && $event->id === $p->eventId) {
                                                $currentUserRole = $p->eventRole;
                                                if ($p->eventRole === 'owner') { $isOwnerMenu = true; }
                                            }
                                        }
                                    }
                                    $roleForPerms = $currentUserRole ?? 'participant';
                                    $canCreateTask = \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'create-task');
                                    $canViewTask = \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'view-task');
                                    $canInvite = \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'manage-invites');
                                    $canManageAnyRole =
                                        \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'manage-participants') ||
                                        \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'manage-taskManagers') ||
                                        \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'manage-taskWorkers') ||
                                        \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'manage-coOwners');
                                    $canEditEvent = \App\Http\RolePermissions\Permissions::hasPermission($roleForPerms, 'edit-event');
                                    $hasMenu = $canCreateTask || $canViewTask || $canInvite || $canManageAnyRole || $canEditEvent || $isOwnerMenu;
                                @endphp
                                @if($hasMenu)
                                <div class="event-kebab rsvp-menu" id="event-menu-{{ $event->id }}">
                                    <button class="kebab-btn rsvp-menu-trigger" onclick="toggleRsvpDropdown('event-menu-{{ $event->id }}')" aria-label="Åbn menu">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="1"></circle><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="17" r="1"></circle></svg>
                                    </button>
                                    <div class="rsvp-menu-list" role="menu" style="right:0; min-width: 200px;">
                                        @auth
                                            @if($canCreateTask)
                                                <a class="rsvp-menu-item" href="{{ route('events.tasks.create.form', ['eventId' => $event->id]) }}">Opret opgave</a>
                                            @endif
                                            @if($canViewTask)
                                                <a class="rsvp-menu-item" href="{{ route('events.tasks.index', ['eventId' => $event->id]) }}">Opgaver</a>
                                            @endif
                                            @if($canInvite)
                                                <a class="rsvp-menu-item" href="/events/{{ $event->id }}?open=invite">Inviter</a>
                                            @endif
                                            @if($canManageAnyRole)
                                                <a class="rsvp-menu-item" href="{{ route('events.participants', ['eventId' => $event->id]) }}">Uddel roller</a>
                                            @endif
                                            @if($canEditEvent)
                                                <a class="rsvp-menu-item" href="/events/{{ $event->id }}/edit">Rediger begivenhed</a>
                                            @endif
                                            @if($isOwnerMenu)
                                                <a class="rsvp-menu-item" href="/events/{{ $event->id }}?open=delete">Slet begivenhed</a>
                                            @endif
                                        @endauth
                                    </div> 
                                </div>
                                @endif
                            </div>
                            <p class="event-description">{{ Str::limit($event->description, 25) }}</p>
                            <div class="event-actions">
                                <a href="/events/{{ $event->id }}" class="btn primary-btn">Se detaljer</a>
                                <button type="button" class="btn secondary-btn" onclick="openParticipantsModal({{ $event->id }}, '{{ $event->eventName }}')">Deltagere</button>
                            </div>
                        </div>
                    @empty
                        <p>Ingen begivenheder fundet.</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
<script>
function toggleRsvpDropdown(id) {
    var m = document.getElementById(id);
    if (!m) return;
    var isOpen = m.classList.contains('open');
    document.querySelectorAll('.rsvp-menu.open').forEach(function(el){ el.classList.remove('open'); });
    if (!isOpen) m.classList.add('open');
}
document.addEventListener('click', function(e){
    var openMenu = document.querySelector('.rsvp-menu.open');
    if (!openMenu) return;
    if (!openMenu.contains(e.target)) {
        openMenu.classList.remove('open');
    }
});
</script>
<script src="{{ asset('js/participants-modal.js') }}"></script>

    <!-- Participants Modal -->
    <div id="participants-modal" class="participants-modal" role="dialog" aria-modal="true" aria-labelledby="participants-modal-title" style="display:none;">
        <div class="participants-modal-content">
            <div class="participants-modal-header">
                <div class="participants-modal-header-content">
                    <div class="participants-modal-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </div>
                    <div class="participants-modal-title">
                        <h2 id="participants-modal-title">Deltagere</h2>
                        <p class="participants-modal-subtitle" id="participants-modal-subtitle">Se alle deltagere for begivenheden</p>
                    </div>
                </div>
                <button class="participants-modal-close-btn" onclick="closeParticipantsModal()" aria-label="Luk">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="participants-modal-body">
                <div class="participants-search-section">
                    <input type="text" id="participants-search" class="participants-search-input" placeholder="Søg efter deltager...">
                    <div class="participants-categories">
                        <button type="button" class="participants-category-btn active" data-category="all">
                            Alle <span class="count" id="count-all">0</span>
                        </button>
                        <button type="button" class="participants-category-btn" data-category="accepted">
                            Deltager <span class="count" id="count-accepted">0</span>
                        </button>
                        <button type="button" class="participants-category-btn" data-category="declined">
                            Deltager ikke <span class="count" id="count-declined">0</span>
                        </button>
                        <button type="button" class="participants-category-btn" data-category="pending">
                            Afventer svar <span class="count" id="count-pending">0</span>
                        </button>
                    </div>
                </div>
                <div class="participants-list-section">
                    <div id="participants-list" class="participants-list">
                        <div class="participants-loading">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                <line x1="12" y1="2" x2="12" y2="6"></line>
                                <line x1="12" y1="18" x2="12" y2="22"></line>
                                <line x1="4.93" y1="4.93" x2="7.76" y2="7.76"></line>
                                <line x1="16.24" y1="16.24" x2="19.07" y2="19.07"></line>
                                <line x1="2" y1="12" x2="6" y2="12"></line>
                                <line x1="18" y1="12" x2="22" y2="12"></line>
                                <line x1="4.93" y1="19.07" x2="7.76" y2="16.24"></line>
                                <line x1="16.24" y1="7.76" x2="19.07" y2="4.93"></line>
                            </svg>
                            Henter deltagere...
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @include('partials.footer')
</body>
</html> 


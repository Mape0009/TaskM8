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
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])

    <main class="main-content-full">

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
                        </div>
                    </div>
                @empty
                    <p>Ingen begivenheder fundet.</p>
                @endforelse
            </div>
        </section>
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
    @include('partials.footer')
</body>
</html> 


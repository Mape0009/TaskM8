<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskM8 Forside</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'dashboard'])

    <main class="main-content-full">
        
        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Afventer svar: </span>
                    <span class="stat-value">{{$pendingEventsCount}}</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Mine Begivenheder: </span>
                    <span class="stat-value">{{$participatedEventsCount}}</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Tidligere Inviterede: </span>
                    <span class="stat-value">{{ $previousInviteesCount }}</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493 M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07 M15 19.128v.106A12.318 12.318 0 0 1 8.624 21 c-2.331 0-4.512-.645-6.374-1.766l-.001-.109 a6.375 6.375 0 0 1 11.964-3.07 M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25 a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
            </div>
        </section>
        <section class="upcoming-events">
            <h2>Kommende Begivenheder</h2>
            <div class="event-list">
                <form action="{{ route('events.index') }}" method="GET">
                @forelse($events as $event)
                    <div class="event-card">
                        <div class="event-header">
                            <h3>{{ $event->eventName }}</h3>
                        </div>
                        <p class="event-description">{{ $event->description }}</p>
                        <div class="event-actions">
                            <a href="/events/{{ $event->id }}" class="btn primary-btn">Se detaljer</a>
                            @auth
                                @php
                                    // Determine current user's participation and role for this event
                                    $myParticipation = \App\Models\EventParticipant::where('eventId', $event->id)
                                        ->where('userId', auth()->id())
                                        ->first();
                                    $rsvpStatus = $myParticipation->status ?? null; // accepted | declined | null
                                    $isParticipant = $rsvpStatus === 'accepted';
                                    $hasResponded = in_array($rsvpStatus, ['accepted','declined']);
                                    $role = $myParticipation?->eventRole ?? 'participant';
                                    // By default, owners and coOwners should not see RSVP controls. Prefer using Permissions if available.
                                    $canRespond = true;
                                    try {
                                        $canRespond = \App\Http\RolePermissions\Permissions::hasPermission($role, 'respond-event');
                                    } catch (\Throwable $e) {
                                        // Fallback: disallow owners/coOwners from responding
                                        $canRespond = !in_array($role, ['owner', 'coOwner']);
                                    }
                                @endphp
                                @if($canRespond)
                                    @php
                                        $isFull = !empty($event->participantLimit) && (\App\Models\EventParticipant::where('eventId', $event->id)->where('status', 'accepted')->count() >= $event->participantLimit) && !$isParticipant;
                                    @endphp
                                    <div class="rsvp-status {{ $rsvpStatus === 'accepted' ? 'accepted' : ($rsvpStatus === 'declined' ? 'declined' : 'pending') }}">
                                        @if($rsvpStatus === 'accepted')
                                            <span class="status-dot"></span> Deltager
                                        @elseif($rsvpStatus === 'declined')
                                            <span class="status-dot"></span> Deltager ikke
                                        @else
                                            <span class="status-dot"></span> Afventer svar
                                        @endif
                                    </div>
                                    <div class="rsvp-menu" id="rsvp-menu-{{ $event->id }}">
                                        <button type="button" class="rsvp-menu-trigger" onclick="toggleRsvpDropdown('rsvp-menu-{{ $event->id }}')">
                                            {{ $hasResponded ? 'Skift svar' : 'Svar' }}
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="caret"><polyline points="6 9 12 15 18 9"></polyline></svg>
                                        </button>
                                        <div class="rsvp-menu-list" role="menu">
                                            <form action="{{ route('events.rsvp', ['eventId' => $event->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="accepted" />
                                                <button type="submit" class="rsvp-menu-item accepted" {{ $isFull ? 'disabled' : '' }}>
                                                    <span class="dot"></span> Deltag
                                                </button>
                                            </form>
                                            <form action="{{ route('events.rsvp', ['eventId' => $event->id]) }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="status" value="declined" />
                                                <button type="submit" class="rsvp-menu-item declined">
                                                    <span class="dot"></span> Deltager ikke
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endif
                            @endauth
                        </div>
                    </div>
                @empty
                    <p>Ingen begivenheder fundet.</p>
                @endforelse
                </form>
            </div>
        </section>
    </main>
    <script src="{{ asset('build/assets/app-DNxiirP_.js') }}" type="module"></script>
</body>
</html>
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
<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 – Planlæg og saml begivenheder';
        $metaDescription = 'Planlæg, inviter og få overblik over alle dine begivenheder i TaskM8.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskM8 Forside</title>
</head>
<body>
    @include('partials.header', ['currentPage' => 'dashboard'])

    <main class="main-content-full">
        {{-- ============================= --}}
        {{--  AUTH: BRUGER ER LOGGET IND   --}}
        {{-- ============================= --}}
        @auth
        <section class="stats-cards">
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Afventer svar: </span>
                    <span class="stat-value">{{ $pendingEventsCount }}</span>
                </div>
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-info">
                    <span class="stat-title">Mine Begivenheder: </span>
                    <span class="stat-value">{{ $participatedEventsCount }}</span>
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
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
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
                            <p class="event-description">{{ Str::limit($event->description, 25) }}</p>

                            @php
                                \Carbon\Carbon::setLocale('da');
                                $start = $event->startDate ? \Carbon\Carbon::parse($event->startDate) : null;
                                $end = $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
                            @endphp

                            <div class="event-dates-text">
                                <p style="margin: 0 0 4px 0; color: var(--color-text-secondary); font-weight: 600;">
                                    Start: {{ $start ? $start->format('j.n.Y') . ' kl ' . $start->format('H:i') : '-' }}
                                </p>
                                <p style="margin: 0 0 20px 0; color: var(--color-text-secondary); font-weight: 600;">
                                    Slut: {{ $end ? $end->format('j.n.Y') . ' kl ' . $end->format('H:i') : '-' }}
                                </p>
                            </div>

                            <div class="event-actions">
                                <a href="/events/{{ $event->id }}" class="btn primary-btn">Se detaljer</a>

                                @php
                                    $isOwner = isset($event->ownerId) && $event->ownerId === auth()->id();
                                    $myParticipation = \App\Models\EventParticipant::where('eventId', $event->id)
                                        ->where('userId', auth()->id())
                                        ->first();
                                    $rsvpStatus = $myParticipation->status ?? null;
                                    $isParticipant = $rsvpStatus === 'accepted';
                                    $hasResponded = in_array($rsvpStatus, ['accepted', 'declined']);
                                @endphp

                                @if(!$isOwner)
                                    @php
                                        $isFull = !empty($event->participantLimit) &&
                                            (\App\Models\EventParticipant::where('eventId', $event->id)
                                                ->where('status', 'accepted')
                                                ->count() >= $event->participantLimit) && !$isParticipant;
                                    @endphp

                                    {{-- RSVP MENU --}}
                                    <div class="rsvp-menu" id="rsvp-menu-{{ $event->id }}">
                                        <button type="button" class="rsvp-menu-trigger" onclick="toggleRsvpDropdown('rsvp-menu-{{ $event->id }}')">
                                            {{ $hasResponded ? 'Skift svar' : 'Svar' }}
                                            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                                 stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="caret">
                                                <polyline points="6 9 12 15 18 9"></polyline>
                                            </svg>
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

                                    {{-- LEAVE KNAP --}}
                                    <button type="button" class="bin-button" aria-label="Forlad begivenhed"
                                            onclick="document.getElementById('leave-modal-{{ $event->id }}').style.display='flex'">
                                        <svg class="bin-top" viewBox="0 0 39 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <line y1="5" x2="39" y2="5" stroke="white" stroke-width="4"></line>
                                            <line x1="12" y1="1.5" x2="26.0357" y2="1.5" stroke="white" stroke-width="3"></line>
                                        </svg>
                                        <svg class="bin-bottom" viewBox="0 0 33 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z" fill="white"/>
                                            <path d="M12 6L12 29" stroke="white" stroke-width="4"/>
                                            <path d="M21 6V29" stroke="white" stroke-width="4"/>
                                        </svg>
                                    </button>

                                    {{-- RSVP STATUS --}}
                                    <div class="rsvp-status {{ $rsvpStatus === 'accepted' ? 'accepted' : ($rsvpStatus === 'declined' ? 'declined' : 'pending') }}">
                                        @if($rsvpStatus === 'accepted')
                                            <span class="status-dot"></span> Deltager
                                        @elseif($rsvpStatus === 'declined')
                                            <span class="status-dot"></span> Deltager ikke
                                        @else
                                            <span class="status-dot"></span> Afventer svar
                                        @endif
                                    </div>

                                    {{-- LEAVE MODAL --}}
                                    <div id="leave-modal-{{ $event->id }}" class="confirm-modal" role="dialog" aria-modal="true" style="display:none;">
                                        <div class="confirm-modal-content">
                                            <div class="confirm-modal-body">
                                                <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon">
                                                    <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9z" fill-rule="evenodd"></path>
                                                </svg>
                                                <h2 class="confirm-title">Er du sikker?</h2>
                                                <p class="confirm-text">Vil du forlade denne begivenhed? Dette kan ikke fortrydes.</p>
                                            </div>
                                            <div class="confirm-actions">
                                                <button type="button" class="confirm-btn cancel" onclick="document.getElementById('leave-modal-{{ $event->id }}').style.display='none'">Annuller</button>
                                                <form action="{{ route('events.decline', ['eventId' => $event->id]) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="confirm-btn danger">Slet</button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </div>
                    @empty
                        <p>Ingen begivenheder fundet.</p>
                    @endforelse
                </form>
            </div>
        </section>
        @endauth

        {{-- ============================= --}}
        {{--  GUEST: IKKE LOGGET IND       --}}
        {{-- ============================= --}}
        @guest
        <div class="guest-landing">
            {{-- Hero, features og CTA --}}
            @include('partials.guest-landing')
        </div>
        @endguest
    </main>

    <script src="{{ asset('build/assets/app-DNxiirP_.js') }}" type="module"></script>

    @guest
        <script src="https://cdn.jsdelivr.net/npm/@formkit/auto-animate@1.0.0-beta.6/dist/auto-animate.min.js"></script>
        <script src="{{ asset('js/landing.js') }}"></script>
    @endguest

    @include('partials.footer')
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

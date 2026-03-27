<!DOCTYPE html>
<html lang="da">
<head>
    @php
        \Carbon\Carbon::setLocale('da');
        $start = isset($event->startDate) && $event->startDate ? \Carbon\Carbon::parse($event->startDate) : null;
        $end = isset($event->endDate) && $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
        $pageTitle = ($event->eventName ?? 'Event Details') . ' | TaskM8';
        $metaDescription = \Illuminate\Support\Str::limit($event->description ?? 'Se detaljer for begivenheden i TaskM8.', 155);
        $heroLead = \Illuminate\Support\Str::limit(trim(strip_tags($event->description ?? 'Planlagt begivenhed i TaskM8.')), 180);
        $eventJson = [
            '@context' => 'https://schema.org',
            '@type' => 'Event',
            'name' => $event->eventName ?? 'Begivenhed',
            'startDate' => $start ? $start->toIso8601String() : null,
            'endDate' => $end ? $end->toIso8601String() : null,
            'eventAttendanceMode' => 'https://schema.org/OfflineEventAttendanceMode',
            'eventStatus' => 'https://schema.org/EventScheduled',
            'description' => strip_tags($event->description ?? ''),
            'location' => [
                '@type' => 'Place',
                'name' => $event->location ?? 'TBD',
            ],
            'image' => asset('TaskM8-Logo.png'),
            'url' => url()->current(),
        ];
        $breadcrumbs = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => [
                [
                    '@type' => 'ListItem',
                    'position' => 1,
                    'name' => 'Forside',
                    'item' => url('/dashboard')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => 'Begivenheder',
                    'item' => url('/events')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $event->eventName ?? 'Begivenhed',
                    'item' => url()->current()
                ],
            ],
        ];
        $structuredData = [$eventJson, $breadcrumbs];
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
        'structuredData' => $structuredData,
    ])
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/invitation.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event-show.css') }}?v={{ filemtime(public_path('css/event-show.css')) }}">
</head>
<body class="event-show-page">
    @include('partials.header', ['currentPage' => 'events'])
    @php
        $acceptedCount = \App\Models\EventParticipant::where('eventId', $event->id)->where('status', 'accepted')->count();
        $isOwnerTop = auth()->check() && isset($event->ownerId) && $event->ownerId === auth()->id();
        $isAcceptedTop = auth()->check() && \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->where('status', 'accepted')->exists();
        $isFullTop = !empty($event->participantLimit) && ($acceptedCount >= $event->participantLimit) && !$isAcceptedTop;
        $myParticipation = auth()->check() ? \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->first() : null;
        $rsvpStatus = $myParticipation->status ?? null;
        $myRole = $myParticipation->eventRole ?? 'participant';
        $canVolunteer = \App\Http\RolePermissions\Permissions::hasPermission($myRole, 'volunteer');
        $hasResponded = in_array($rsvpStatus, ['accepted', 'declined']);
        $participantLimit = !empty($event->participantLimit) ? (int) $event->participantLimit : null;
        $spotsLeft = $participantLimit ? max($participantLimit - $acceptedCount, 0) : null;
        $attendancePercent = $participantLimit ? min(100, (int) round(($acceptedCount / max($participantLimit, 1)) * 100)) : null;
        $durationLabel = '-';
        if ($start && $end && $end->greaterThan($start)) {
            $durationMinutes = $start->diffInMinutes($end);
            $durationHours = intdiv($durationMinutes, 60);
            $remainingMinutes = $durationMinutes % 60;

            if ($durationHours > 0 && $remainingMinutes > 0) {
                $durationLabel = $durationHours . ' t ' . $remainingMinutes . ' min';
            } elseif ($durationHours > 0) {
                $durationLabel = $durationHours . ' t';
            } else {
                $durationLabel = $remainingMinutes . ' min';
            }
        }
    @endphp
    <main class="main-content-full event-page-shell">
        <section class="event-page-grid">
            <article class="event-surface event-surface--content">
                <header class="event-content-top">
                    <span class="event-content-kicker">Begivenhed</span>
                    <h1 class="event-content-title">{{ $event->eventName ?? 'Begivenhed' }}</h1>
                    <p class="event-content-summary">{{ $heroLead }}</p>
                </header>

                <div class="event-overview-grid">
                    <div class="event-overview-item">
                        <span class="event-overview-item__label">Lokation</span>
                        <strong class="event-overview-item__value">{{ $event->location ?? 'Ukendt lokation' }}</strong>
                    </div>
                    <div class="event-overview-item">
                        <span class="event-overview-item__label">Start</span>
                        <strong class="event-overview-item__value">{{ $start ? $start->translatedFormat('D d. M Y') . ' kl. ' . $start->format('H:i') : '-' }}</strong>
                    </div>
                    <div class="event-overview-item">
                        <span class="event-overview-item__label">Varighed</span>
                        <strong class="event-overview-item__value">{{ $durationLabel }}</strong>
                    </div>
                    <div class="event-overview-item">
                        <span class="event-overview-item__label">Deltagere</span>
                        <strong class="event-overview-item__value">{{ $acceptedCount }}@if($participantLimit)/{{ $participantLimit }}@endif</strong>
                    </div>
                </div>

                <section class="event-section-header">
                    <h2>Beskrivelse</h2>
                </section>

                <div class="event-description-block">
                    {{ $event->description ?? 'Der er ingen beskrivelse af denne begivenhed.' }}
                </div>

                <section class="event-section-header event-section-header--compact">
                    <h2>Praktisk info</h2>
                </section>

                <dl class="event-facts-list">
                    <div class="event-fact-row">
                        <dt>Start</dt>
                        <dd>{{ $start ? $start->translatedFormat('l d. F Y') . ' kl. ' . $start->format('H:i') : '-' }}</dd>
                    </div>
                    <div class="event-fact-row">
                        <dt>Slut</dt>
                        <dd>{{ $end ? $end->translatedFormat('l d. F Y') . ' kl. ' . $end->format('H:i') : '-' }}</dd>
                    </div>
                    <div class="event-fact-row">
                        <dt>Status</dt>
                        <dd>{{ $isFullTop ? 'Fyldt op' : 'Åben for deltagere' }}</dd>
                    </div>
                    <div class="event-fact-row">
                        <dt>Ledige pladser</dt>
                        <dd>{{ $spotsLeft ?? 'Fri kapacitet' }}</dd>
                    </div>
                </dl>

                @auth
                @if(!$isOwnerTop && session('success'))
                    <div class="rsvp-flash">{{ session('success') }}</div>
                @endif
                @endauth
            </article>

            <aside class="event-surface event-surface--actions">
                <h2 class="event-actions-title">Handlinger</h2>

                <a href="/events" class="back-btn">Tilbage til begivenheder</a>

                <div class="event-attendance-card">
                    <div class="event-attendance-card__top">
                        <span class="event-attendance-card__label">Belægning</span>
                        <strong>
                            {{ $acceptedCount }}@if($participantLimit)/{{ $participantLimit }}@endif
                        </strong>
                    </div>
                    @if(!is_null($attendancePercent))
                        <div class="event-attendance-progress" aria-hidden="true">
                            <span style="width: {{ $attendancePercent }}%"></span>
                        </div>
                        <p class="event-attendance-card__meta">{{ $spotsLeft }} pladser tilbage</p>
                    @else
                        <p class="event-attendance-card__meta">Ingen øvre deltagergrænse på denne begivenhed.</p>
                    @endif
                </div>

                @auth
                @if($isOwnerTop)
                    <div class="event-actions-details event-actions-details--column">
                        <button class="btn invite-btn" onclick="openInviteModal({{ $event->id }}, '{{ $event->eventName }}')">Inviter deltagere</button>
                        <button type="button" class="btn event-danger-btn" onclick="openDeleteModal()">Slet begivenhed</button>
                    </div>
                @else
                    <div class="event-actions-details event-actions-details--column" aria-label="Deltagelsesstatus">
                        @if($canVolunteer && $myRole !== \App\Models\EventRole::volunteer->name)
                            <form action="{{ route('events.volunteer', ['eventId' => $event->id]) }}" method="POST" style="width:100%;">
                                @csrf
                                <button type="submit" class="btn invite-btn" style="width:100%; margin-bottom:0.75rem;">Bliv frivillig</button>
                            </form>
                        @elseif($myRole === \App\Models\EventRole::volunteer->name)
                            <form action="{{ route('events.unvolunteer', ['eventId' => $event->id]) }}" method="POST" style="width:100%;">
                                @csrf
                                <button type="submit" class="btn event-danger-btn" style="width:100%; margin-bottom:0.75rem;">Stop frivillig</button>
                            </form>
                        @endif
                        <div class="rsvp-status {{ $rsvpStatus === 'accepted' ? 'accepted' : ($rsvpStatus === 'declined' ? 'declined' : 'pending') }}">
                            @if($rsvpStatus === 'accepted')
                                <span class="status-dot"></span> Deltager
                            @elseif($rsvpStatus === 'declined')
                                <span class="status-dot"></span> Deltager ikke
                            @else
                                <span class="status-dot"></span> Afventer svar
                            @endif
                        </div>

                        <div class="rsvp-menu" id="rsvp-menu-event">
                            <button type="button" class="rsvp-menu-trigger" onclick="toggleRsvpDropdown('rsvp-menu-event')">
                                {{ $hasResponded ? 'Skift svar' : 'Svar' }}
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="caret"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                            <div class="rsvp-menu-list" role="menu">
                                <form action="{{ route('events.rsvp', ['eventId' => $event->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="accepted" />
                                    <button type="submit" class="rsvp-menu-item accepted" {{ $isFullTop ? 'disabled' : '' }}>
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
                    </div>
                @endif
                @endauth
            </aside>
        </section>
    </main>

    @auth
    @php
        $isOwnerMenu = isset($event->ownerId) && $event->ownerId === auth()->id();
    @endphp
    @if($isOwnerMenu)
    <!-- Delete Confirmation Modal -->
    <div id="delete-modal" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirm-title" style="display:none;">
        <div class="confirm-modal-content">
            <div class="confirm-modal-body">
                <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
                </svg>
                <h2 id="confirm-title" class="confirm-title">Er du sikker?</h2>
                <p class="confirm-text">Vil du slette denne begivenhed? Dette kan ikke fortrydes.</p>
            </div>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn cancel" onclick="closeDeleteModal()">Annuller</button>
                <form id="delete-event-form" action="{{ url('/events/delete/'.$event->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="confirm-btn danger">Slet</button>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endauth
    
    <!-- Invitation Modal -->
    <div id="invite-modal" class="invite-modal">
        <div class="invite-modal-content">
            <div class="invite-modal-header">
                <div class="modal-header-content">
                    <span class="modal-icon">
                        <svg width="28" height="28" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <path d="M16 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"></path>
                            <circle cx="8.5" cy="7" r="4"></circle>
                            <line x1="20" y1="8" x2="20" y2="14"></line>
                            <line x1="23" y1="11" x2="17" y2="11"></line>
                        </svg>
                    </span>
                    <div class="modal-title">
                        <h2>Inviter til begivenhed</h2>
                        <p class="modal-subtitle">Vælg hvordan du vil tilføje modtagere: nye e‑mails, tidligere inviterede eller hele grupper.</p>
                    </div>
                </div>
                <button class="modal-close-btn" onclick="closeInviteModal()" aria-label="Luk">
                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="invite-modal-form">
                <form action="{{ route('events.invite', $event->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="eventIdInvite" value="{{ $event->id }}">
                    <div class="invite-category-tabs">
                        <div class="invite-category-list" role="tablist" aria-label="Vælg modtager-type">
                            <button type="button" class="invite-category-btn is-active" data-target="new" role="tab" aria-selected="true">
                                Nye e-mails
                            </button>
                            <button type="button" class="invite-category-btn" data-target="previous" role="tab" aria-selected="false">
                                Tidligere inviterede
                            </button>
                            <button type="button" class="invite-category-btn" data-target="groups" role="tab" aria-selected="false">
                                Grupper
                            </button>
                        </div>
                    </div>

                    <div class="invite-section is-active" data-section="new">
                        <div class="section-heading">
                            <div>
                                <h3>Nye emails</h3>
                                <p class="invite-section-subtitle">Tilføj nye personer med det samme – ét eller flere adresser ad gangen.</p>
                            </div>
                            <span class="pill soft">Ny</span>
                        </div>
                        <div class="email-input-container">
                            <div class="email-input-group">
                                <input type="email" id="email-input" placeholder="Søg/indtast email adresse" class="email-input">
                                <button type="button" onclick="addEmail()" class="add-email-btn">Tilføj</button>
                            </div>
                        </div>
                        <div id="email-list" class="email-list"></div>
                    </div>

                    <div class="invite-section" data-section="previous">
                        <div class="section-heading">
                            <div>
                                <h3>Tidligere inviterede</h3>
                                <p class="invite-section-subtitle">Find og geninviter personer du allerede har kontaktet.</p>
                            </div>
                            <span class="pill neutral">Historik</span>
                        </div>
                        <div class="search-container">
                            <div class="search-input-wrapper">
                                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <input type="text" id="search-invitees" placeholder="Søg efter tidligere inviterede..." class="search-input">
                            </div>
                        </div>
                        <div id="invitees-list" class="invitees-list"></div>
                    </div>

                    <div class="invite-section" data-section="groups">
                        <div class="section-heading">
                            <div>
                                <h3>Grupper</h3>
                                <p class="invite-section-subtitle">Inviter et helt hold på én gang. Vi tilføjer alle medlemmers e-mails til listen.</p>
                            </div>
                            <span class="pill accent">Hold</span>
                        </div>
                        <div class="search-container group-search-container">
                            <div class="search-input-wrapper">
                                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <input type="text" id="search-groups" placeholder="Søg efter en gruppe..." class="search-input">
                            </div>
                        </div>
                        <div id="groups-list" class="groups-list grid"></div>
                    </div>
                    
                    <div class="invite-footer">
                        <span class="invite-count" id="invite-count-label">Ingen modtagere valgt endnu.</span>
                        <button type="button" onclick="sendInvitations()" class="btn primary-btn" id="send-invitations-btn" disabled>Send invitationer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="{{ asset('js/event.js') }}"></script>
    <script src="{{ asset('js/invitation.js') }}"></script>

    <script>
        function toggleRsvpForm(formId) {
            var f = document.getElementById(formId);
            if (!f) return;
            f.style.display = (f.style.display === 'none' || f.style.display === '') ? 'flex' : 'none';
        }
        let currentEventId = null;
        let addedEmails = [];
        let previousInvitees = [];
        const groupsData = @json($groups ?? []);
        function openDeleteModal() {
            var m = document.getElementById('delete-modal');
            if (m) { m.style.display = 'flex'; }
        }
        function closeDeleteModal() {
            var m = document.getElementById('delete-modal');
            if (m) { m.style.display = 'none'; }
        }

        function toggleRsvpDropdown(menuId) {
            var m = document.getElementById(menuId);
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

        function openInviteModal(eventId, eventName) {
            currentEventId = eventId;
            document.getElementById('invite-modal').style.display = 'flex';
            loadPreviousInvitees();
            try {
                const defaultTab = document.querySelector('.invite-category-btn[data-target="new"]');
                if (defaultTab) {
                    switchInviteCategory(defaultTab);
                }
            } catch (_) {}
            // If user chose to keep members from a template, prefill emails once
            try {
                const payloadRaw = localStorage.getItem('template_keep_members_payload');
                if (payloadRaw) {
                    const payload = JSON.parse(payloadRaw);
                    if (payload && payload.eventId) {
                        (payload.emails || []).forEach(e => { if (!addedEmails.includes(e)) addedEmails.push(e); });
                        updateEmailList();
                        // Clear after first use
                        localStorage.removeItem('template_keep_members_payload');
                        localStorage.removeItem('template_keep_members_event_id');
                    }
                }
            } catch(_) {}
        }

        function closeInviteModal() {
            document.getElementById('invite-modal').style.display = 'none';
            document.getElementById('email-input').value = '';
            document.getElementById('search-invitees').value = '';
            addedEmails = [];
            updateEmailList();
        }

        function addEmail() {
            const emailInput = document.getElementById('email-input');
            const email = emailInput.value.trim();
            if (email && isValidEmail(email) && !addedEmails.includes(email))
            {
                addedEmails.push(email);
                emailInput.value = '';
                updateEmailList();
            }
        }

        function removeEmail(email) {
            addedEmails = addedEmails.filter(e => e !== email);
            updateEmailList();
        }

        function updateEmailList() {
            const emailList = document.getElementById('email-list');
            emailList.innerHTML = '';
            addedEmails.forEach(email => {
                const emailTag = document.createElement('div');
                emailTag.className = 'email-tag';
                emailTag.innerHTML = `
                    <span>${email}</span>
                    <button type="button" onclick="removeEmail('${email}')" class="remove-email-btn">
                        <svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                            <line x1="18" y1="6" x2="6" y2="18"></line>
                            <line x1="6" y1="6" x2="18" y2="18"></line>
                        </svg>
                    </button>
                `;
                emailList.appendChild(emailTag);
            });

            const countLabel = document.getElementById('invite-count-label');
            const sendBtn = document.getElementById('send-invitations-btn');
            const count = addedEmails.length;
            if (count === 0) {
                if (countLabel) countLabel.textContent = 'Ingen modtagere valgt endnu.';
            } else if (count === 1) {
                if (countLabel) countLabel.textContent = '1 modtager klar til invitation.';
            } else {
                if (countLabel) countLabel.textContent = count + ' modtagere klar til invitation.';
            }
            if (sendBtn) {
                sendBtn.disabled = count === 0;
            }
        }

        function isValidEmail(email) {
            const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/i;
            return emailRegex.test(email);
        }

        function renderGroups(filter = '') {
            const groupsList = document.getElementById('groups-list');
            if (!groupsList) return;
            groupsList.innerHTML = '';
            const normalized = filter.trim().toLowerCase();
            const matches = groupsData
                .filter(g => (g.name || '').toLowerCase().includes(normalized))
                .slice(0, 3);
            if (!matches.length) {
                groupsList.innerHTML = '<div class="group-card empty">Ingen grupper matcher din søgning.</div>';
                return;
            }
            matches.forEach(group => {
                const card = document.createElement('div');
                card.className = 'group-card';
                card.innerHTML = `
                    <div class="group-avatar" style="background:${stringToColor(group.name)}">${group.name.charAt(0)}</div>
                    <div class="group-meta">
                        <div class="group-name">${group.name}</div>
                        <div class="group-sub">${group.members ?? 0} medlemmer</div>
                    </div>
                    <button type="button" class="group-action" title="Tilføj alle medlemmer fra denne gruppe">Tilføj</button>
                `;
                card.querySelector('.group-action').addEventListener('click', () => addGroupToInvites(group));
                groupsList.appendChild(card);
            });
        }

        async function addGroupToInvites(group) {
            try {
                const response = await fetch(`/groups/members/${group.id}`, {
                    headers: {
                        'Accept': 'application/json'
                    }
                });
                if (!response.ok) {
                    console.error('Kunne ikke hente gruppemedlemmer', await response.text());
                    return;
                }
                const members = await response.json();
                (members || []).forEach(m => {
                    if (m.email && !addedEmails.includes(m.email)) {
                        addedEmails.push(m.email);
                    }
                });
                updateEmailList();
            } catch (e) {
                console.error('Fejl ved tilføjelse af gruppe til invitationer', e);
            }
        }

        function switchInviteCategory(button) {
            const target = button.dataset.target;
            const buttons = Array.from(document.querySelectorAll('.invite-category-btn'));
            const sections = Array.from(document.querySelectorAll('.invite-section'));

            buttons.forEach(b => {
                const isActive = b === button;
                b.classList.toggle('is-active', isActive);
                b.setAttribute('aria-selected', isActive ? 'true' : 'false');
            });

            sections.forEach(section => {
                const sectionKey = section.dataset.section;
                section.classList.toggle('is-active', sectionKey === target);
            });
        }

        function stringToColor(str = '') {
            let hash = 0;
            for (let i = 0; i < str.length; i++) {
                hash = str.charCodeAt(i) + ((hash << 5) - hash);
            }
            const h = hash % 360;
            return `hsl(${h}, 70%, 60%)`;
        }

        async function loadPreviousInvitees() {
            const inviteesList = document.getElementById('invitees-list');
            inviteesList.innerHTML = '<div class="invitee-item">Henter inviterede...</div>';
            try {
                const res = await fetch('{{ route('events.invitees', $event->id) }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Failed');
                previousInvitees = await res.json() || [];
                renderInvitees();
            } catch (e) {
                inviteesList.innerHTML = '<div class="invitee-item">Kunne ikke hente inviterede.</div>';
            }
        }

        function renderInvitees(filter = '') {
            const inviteesList = document.getElementById('invitees-list');
            const normalized = filter.trim().toLowerCase();
            const items = (previousInvitees || [])
                .filter(i => ((i.name || '').toLowerCase().includes(normalized) || (i.email || '').toLowerCase().includes(normalized)))
                .slice(0, 3);
            if (!items.length) {
                    inviteesList.innerHTML = '<div class="invitee-item">Ingen tidligere inviterede</div>';
                    return;
                }
            inviteesList.innerHTML = '';
            items.forEach(i => {
                const initials = (i.name || i.email || '?').trim().charAt(0).toUpperCase();
                const item = document.createElement('div');
                item.className = 'invitee-item';
                item.innerHTML = `
                    <div class="invitee-info">
                        <div class="invitee-avatar">${initials}</div>
                        <div class="invitee-details">
                            <span class="invitee-name">${i.name || 'Ukendt'}</span>
                            <span class="invitee-email">${i.email}</span>
                        </div>
                    </div>
                    <button type="button" class="invitee-select-btn">Vælg</button>
                `;
                item.querySelector('.invitee-select-btn').addEventListener('click', () => selectInvitee(i.email));
                inviteesList.appendChild(item);
            });
        }

        function selectInvitee(email) {
            if (!addedEmails.includes(email)) {
                addedEmails.push(email);
                updateEmailList();
            }
        }

        function sendInvitations() {
            const form = document.querySelector('#invite-modal form');

            form.querySelectorAll('input[name="emailsInvite[]"]').forEach(el => el.remove());

            // Tilføj emails som hidden inputs
            addedEmails.forEach(email => {
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'emailsInvite[]';
                hiddenInput.value = email;
                form.appendChild(hiddenInput);
            });

            // Undgå at modalen åbnes igen efter redirect
            try { localStorage.setItem('skip_invite_modal_once', '1'); } catch (_) {}
            const sendBtn = document.getElementById('send-invitations-btn');
            if (sendBtn) {
                sendBtn.disabled = true;
                sendBtn.textContent = 'Sender...';
            }

            form.submit();
        }

        document.getElementById('email-input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                e.preventDefault();
                addEmail();
            }
        });

        document.getElementById('search-groups').addEventListener('input', function(e) {
            renderGroups(e.target.value || '');
        });

        document.getElementById('search-invitees').addEventListener('input', function(e) {
            renderInvitees(e.target.value || '');
        });

        document.querySelectorAll('.invite-category-btn').forEach(btn => {
            btn.addEventListener('click', function () {
                switchInviteCategory(this);
            });
        });

        document.getElementById('invite-modal').addEventListener('click', function(e) {
            if (e.target === this) {
                closeInviteModal();
            }
        });
        var deleteModalEl = document.getElementById('delete-modal');
        if (deleteModalEl) {
            deleteModalEl.addEventListener('click', function(e) {
                if (e.target === this) { closeDeleteModal(); }
            });
        }

        // Auto-open based on query parameter (?open=invite|delete)
        (function(){
            try {
                var params = new URLSearchParams(window.location.search);
                var open = params.get('open');
                var skip = localStorage.getItem('skip_invite_modal_once') === '1';
                if (skip) { localStorage.removeItem('skip_invite_modal_once'); }
                if(open === 'invite' && !skip) { openInviteModal({{ $event->id }}, '{{ $event->eventName }}'); }
                if(open === 'delete') { openDeleteModal(); }

                // Fjern open-parameteren fra URL for at undgå re-open
                if (open) {
                    params.delete('open');
                    var newQuery = params.toString();
                    var newUrl = window.location.pathname + (newQuery ? ('?' + newQuery) : '') + window.location.hash;
                    window.history.replaceState({}, '', newUrl);
                }
            } catch(_) {}
        })();

        // Initial render for mock group list so it is visible when modal åbnes via query param
        renderGroups();
    </script>
    @include('partials.footer')
</body>
</html>


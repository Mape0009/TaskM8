<!DOCTYPE html>
<html lang="da">
<head>
    @php
        \Carbon\Carbon::setLocale('da');
        $start = isset($event->startDate) && $event->startDate ? \Carbon\Carbon::parse($event->startDate) : null;
        $end = isset($event->endDate) && $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
        $pageTitle = ($event->eventName ?? 'Event Details') . ' | TaskM8';
        $metaDescription = \Illuminate\Support\Str::limit($event->description ?? 'Se detaljer for begivenheden i TaskM8.', 155);
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
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/invitation.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'events'])
    <main class="event-hero-bg">
        <section class="event-hero-section">
            <div class="event-hero-icon">
                <svg width="44" height="44" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
            </div>
            <h1 class="event-hero-title">{{ $event->eventName ?? 'Event Title' }}</h1>
            
            @php
                \Carbon\Carbon::setLocale('da');
                $start = $event->startDate ? \Carbon\Carbon::parse($event->startDate) : null;
                $end = $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
            @endphp
        </section>
        
        <section class="event-details-card">
            <div class="event-card-actions-top">
                <a href="/" class="back-btn">Tilbage</a>

                @auth
                @php
                    $isOwnerTop = isset($event->ownerId) && $event->ownerId === auth()->id();
                    $isAcceptedTop = \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->where('status', 'accepted')->exists();
                    $isFullTop = !empty($event->participantLimit) && (\App\Models\EventParticipant::where('eventId', $event->id)->where('status', 'accepted')->count() >= $event->participantLimit) && !$isAcceptedTop;
                    $myParticipation = \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->first();
                    $rsvpStatus = $myParticipation->status ?? null;
                    $hasResponded = in_array($rsvpStatus, ['accepted','declined']);
                @endphp
                @if($isOwnerTop)
                    <div class="event-actions-details">
                        <button class="btn invite-btn" onclick="openInviteModal({{ $event->id }}, '{{ $event->eventName }}')">Inviter</button>
                        <button type="button" class="bin-button" aria-label="Slet begivenhed" onclick="openDeleteModal()">
                            <svg class="bin-top" viewBox="0 0 39 7" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <line y1="5" x2="39" y2="5" stroke="white" stroke-width="4"></line>
                                <line x1="12" y1="1.5" x2="26.0357" y2="1.5" stroke="white" stroke-width="3"></line>
                            </svg>
                            <svg class="bin-bottom" viewBox="0 0 33 39" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <mask id="path-1-inside-1_8_19" fill="white">
                                    <path d="M0 0H33V35C33 37.2091 31.2091 39 29 39H4C1.79086 39 0 37.2091 0 35V0Z"></path>
                                </mask>
                                <path d="M0 0H33H0ZM37 35C37 39.4183 33.4183 43 29 43H4C-0.418278 43 -4 39.4183 -4 35H4H29H37ZM4 43C-0.418278 43 -4 39.4183 -4 35V0H4V35V43ZM37 0V35C37 39.4183 33.4183 43 29 43V35V0H37Z" fill="white" mask="url(#path-1-inside-1_8_19)"></path>
                                <path d="M12 6L12 29" stroke="white" stroke-width="4"></path>
                                <path d="M21 6V29" stroke="white" stroke-width="4"></path>
                            </svg>
                        </button>
                    </div>
                @else
                    <div class="event-actions-details" aria-label="Deltagelsesstatus">
                        <div class="rsvp-status {{ $rsvpStatus === 'accepted' ? 'accepted' : ($rsvpStatus === 'declined' ? 'declined' : 'pending') }}">
                            @if($rsvpStatus === 'accepted')
                                <span class="status-dot"></span> Deltager
                            @elseif($rsvpStatus === 'declined')
                                <span class="status-dot"></span> Deltager ikke
                            @else
                                <span class="status-dot"></span> Afventer svar
                            @endif
                        </div>
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
                @endif
                @endauth
            </div>
            <ul class="event-details-list">
                <li><span class="event-details-label">Lokation:</span> <span class="event-details-value">{{ $event->location ?? '-' }}</span></li>
                <li>
                    <span class="event-details-label">Start:</span>
                    <span class="event-details-value">{{ $start ? $start->translatedFormat('l d. F Y') . ' kl. ' . $start->format('H:i') : '-' }}</span>
                </li>
                <li>
                    <span class="event-details-label">Slut:</span>
                    <span class="event-details-value">{{ $end ? $end->translatedFormat('l d. F Y') . ' kl. ' . $end->format('H:i') : '-' }}</span>
                </li>
                @php
                    $acceptedCount = \App\Models\EventParticipant::where('eventId', $event->id)->where('status', 'accepted')->count();
                @endphp
                <li>
                    <span class="event-details-label">Deltagere:</span>
                    <span class="event-details-value">
                        {{ $acceptedCount }}
                        @if(!empty($event->participantLimit))
                            / {{ $event->participantLimit }}
                        @endif
                    </span>
                </li>
            </ul>
            <div class="event-details-description">
                {{ $event->description ?? 'Der er ingen beskrivelse af denne begivenhed.' }}
            </div>
            @php
                $limit = $event->participantLimit ?? null;
                $current = $acceptedCount;
                $pct = ($limit && $limit > 0) ? min(100, max(0, round(($current / max(1,$limit)) * 100))) : null;
            @endphp
            @auth
            @php
                $isOwner = isset($event->ownerId) && $event->ownerId === auth()->id();
            @endphp
            @if(!$isOwner && session('success'))
                <div class="rsvp-flash">{{ session('success') }}</div>
            @endif
            @endauth
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


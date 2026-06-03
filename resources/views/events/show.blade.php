<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        \Carbon\Carbon::setLocale(app()->getLocale());
        $start = isset($event->startDate) && $event->startDate ? \Carbon\Carbon::parse($event->startDate) : null;
        $end = isset($event->endDate) && $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
        $pageTitle = 'TaskM8 | ' . ($event->eventName ?? __('ui.event_overview'));
        $metaDescription = \Illuminate\Support\Str::limit($event->description ?? __('ui.back_to_events'), 155);
        $heroLead = \Illuminate\Support\Str::limit(trim(strip_tags($event->description ?? __('ui.guest_subtitle'))), 180);
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
                    'name' => __('ui.page_title_dashboard'),
                    'item' => url('/dashboard')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 2,
                    'name' => __('ui.events'),
                    'item' => url('/events')
                ],
                [
                    '@type' => 'ListItem',
                    'position' => 3,
                    'name' => $event->eventName ?? __('ui.event_overview'),
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
        $isAcceptedParticipant = $rsvpStatus === 'accepted';
        $isVolunteerNow = $myRole === \App\Models\EventRole::volunteer->name;
        $canShowVolunteerButton = !$isOwnerTop && $isAcceptedParticipant && $canVolunteer;
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
            <article class="event-surface event-surface--content event-surface--hero">
                <header class="event-content-top">
                    <h1 class="event-content-title">{{ $event->eventName ?? __('ui.event_overview') }}</h1>
                </header>

                <section class="event-panel event-panel--secondary">
                    <div class="event-panel__header">
                        <h2>{{ __('ui.event_overview') }}</h2>
                    </div>
                    <dl class="event-facts-list event-facts-list--compact">
                        <div class="event-fact-row">
                            <dt>{{ __('ui.location') }}</dt>
                            <dd>{{ $event->location ?? __('ui.unknown') }}</dd>
                        </div>
                        <div class="event-fact-row">
                            <dt>{{ __('ui.start_time') }}</dt>
                            <dd>{{ $start ? $start->translatedFormat('l d. F Y') . ' kl. ' . $start->format('H:i') : '-' }}</dd>
                        </div>
                        <div class="event-fact-row">
                            <dt>{{ __('ui.end_time') }}</dt>
                            <dd>{{ $end ? $end->translatedFormat('l d. F Y') . ' kl. ' . $end->format('H:i') : '-' }}</dd>
                        </div>
                        <div class="event-fact-row">
                            <dt>{{ __('ui.event_status') }}</dt>
                            <dd>{{ $isFullTop ? __('ui.event_full') : __('ui.event_open') }}</dd>
                        </div>
                    </dl>
                </section>

                <section class="event-panel event-panel--primary">
                    <div class="event-panel__header">
                        <h2>{{ __('ui.about_event') }}</h2>
                    </div>
                    <div class="event-description-block event-description-block--hero">
                        {{ $event->description ?? __('ui.no_events_found') }}
                    </div>
                </section>

            </article>

            <aside class="event-surface event-surface--actions">
                <div class="event-actions-head">
                    <h2 class="event-actions-title">{{ __('ui.next_steps') }}</h2>
                    <p>{{ __('ui.overview_hint') }}</p>
                </div>

                <a href="/events" class="back-btn">{{ __('ui.back_to_events') }}</a>

                <div class="event-attendance-card">
                    <div class="event-attendance-card__top">
                        <span class="event-attendance-card__label">{{ __('ui.event_participation') }}</span>
                        <strong>{{ $acceptedCount }}@if($participantLimit)/{{ $participantLimit }}@endif</strong>
                    </div>
                    @if(!is_null($attendancePercent))
                        <div class="event-attendance-progress" aria-hidden="true">
                            <span style="width: {{ $attendancePercent }}%"></span>
                        </div>
                        <p class="event-attendance-card__meta">{{ __('ui.spots_left', ['count' => $spotsLeft]) }}</p>
                    @else
                        <p class="event-attendance-card__meta">{{ __('ui.no_participant_limit') }}</p>
                    @endif

                </div>

                @auth
                @if($isOwnerTop)
                    <div class="event-actions-details event-actions-details--column">
                        <button class="btn invite-btn" onclick="openInviteModal({{ $event->id }}, '{{ $event->eventName }}')">{{ __('ui.invite_participants') }}</button>
                        <button type="button" class="btn event-danger-btn" onclick="openDeleteModal()">{{ __('ui.delete_event_button') }}</button>
                    </div>
                @else
                    <div class="event-actions-details event-actions-details--column" aria-label="{{ __('ui.event_participation') }}">
                        @if(!$isOwnerTop && $rsvpStatus)
                            <div class="event-inline-status-wrap" aria-label="{{ __('ui.your_status') }}">
                                <span class="event-inline-status-label">{{ __('ui.your_status') }}</span>
                                <div class="rsvp-status {{ $rsvpStatus === 'accepted' ? 'accepted' : ($rsvpStatus === 'declined' ? 'declined' : 'pending') }}">
                                    @if($rsvpStatus === 'accepted')
                                        <span class="status-dot"></span> {{ __('ui.attending') }}
                                    @elseif($rsvpStatus === 'declined')
                                        <span class="status-dot"></span> {{ __('ui.not_attending') }}
                                    @else
                                        <span class="status-dot"></span> {{ __('ui.awaiting_response') }}
                                    @endif
                                </div>
                            </div>
                        @endif

                        @if($canVolunteer && $myRole !== \App\Models\EventRole::volunteer->name)
                            <form action="{{ route('events.volunteer', ['eventId' => $event->id]) }}" method="POST" style="width:100%;">
                                @csrf
                                <button type="submit" class="btn invite-btn" style="width:100%; margin-bottom:0.75rem;">{{ __('ui.become_volunteer') }}</button>
                            </form>
                        @elseif($myRole === \App\Models\EventRole::volunteer->name)
                            <form action="{{ route('events.unvolunteer', ['eventId' => $event->id]) }}" method="POST" style="width:100%;">
                                @csrf
                                <button type="submit" class="btn event-danger-btn" style="width:100%; margin-bottom:0.75rem;">{{ __('ui.stop_volunteer') }}</button>
                            </form>
                        @endif

                        <div class="rsvp-menu" id="rsvp-menu-event">
                            <button type="button" class="rsvp-menu-trigger" onclick="toggleRsvpDropdown('rsvp-menu-event')">
                                {{ $hasResponded ? __('ui.status_confirm') : __('ui.answer') }}
                                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="caret"><polyline points="6 9 12 15 18 9"></polyline></svg>
                            </button>
                            <div class="rsvp-menu-list" role="menu">
                                <form action="{{ route('events.rsvp', ['eventId' => $event->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="accepted" />
                                    <button type="submit" class="rsvp-menu-item accepted" {{ $isFullTop ? 'disabled' : '' }}>
                                        <span class="dot"></span> {{ __('ui.attending') }}
                                    </button>
                                </form>
                                <form action="{{ route('events.rsvp', ['eventId' => $event->id]) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="declined" />
                                    <button type="submit" class="rsvp-menu-item declined">
                                        <span class="dot"></span> {{ __('ui.not_attending') }}
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
                <h2 id="confirm-title" class="confirm-title">{{ __('ui.confirm_are_you_sure') }}</h2>
                <p class="confirm-text">{{ __('ui.confirm_delete_event') }}</p>
            </div>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn cancel" onclick="closeDeleteModal()">{{ __('ui.cancel') }}</button>
                <form id="delete-event-form" action="{{ url('/events/delete/'.$event->id) }}" method="POST" style="display:inline;">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="confirm-btn danger">{{ __('ui.delete') }}</button>
                </form>
            </div>
        </div>
    </div>
    @endif
    @endauth

    @auth
    @if($canShowVolunteerButton)
    <div id="volunteer-confirm-modal" class="volunteer-confirm-modal" role="dialog" aria-modal="true" aria-labelledby="volunteer-confirm-title" style="display:none;">
        <div class="volunteer-confirm-modal__dialog">
            <div class="volunteer-confirm-modal__header">
                <h2 id="volunteer-confirm-title">{{ __('ui.confirm_volunteer_title') }}</h2>
                <button type="button" class="volunteer-confirm-modal__close" onclick="closeVolunteerConfirm()" aria-label="Luk">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>
                </button>
            </div>
            <div class="volunteer-confirm-modal__body">
                <p id="volunteer-confirm-text">{{ __('ui.confirm_volunteer_text') }}</p>
            </div>
            <div class="volunteer-confirm-modal__actions">
                <button type="button" class="btn secondary-btn" onclick="closeVolunteerConfirm()">{{ __('ui.cancel') }}</button>

                <form id="volunteer-join-form" action="{{ route('events.volunteer', ['eventId' => $event->id]) }}" method="POST" style="display:none;">
                    @csrf
                    <button type="submit" class="btn primary-btn">{{ __('ui.yes_become_volunteer') }}</button>
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
                        <h2>{{ __('ui.invite_to_event') }}</h2>
                        <p class="modal-subtitle">{{ __('ui.invite_intro') }}</p>
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
                                {{ __('ui.new_emails') }}
                            </button>
                            <button type="button" class="invite-category-btn" data-target="previous" role="tab" aria-selected="false">
                                {{ __('ui.previous_invitees') }}
                            </button>
                            <button type="button" class="invite-category-btn" data-target="groups" role="tab" aria-selected="false">
                                {{ __('ui.groups_title') }}
                            </button>
                        </div>
                    </div>

                    <div class="invite-section is-active" data-section="new">
                        <div class="section-heading">
                            <div>
                                <h3>{{ __('ui.new_emails') }}</h3>
                                <p class="invite-section-subtitle">{{ __('ui.new_emails_intro') }}</p>
                            </div>
                            <span class="pill soft">{{ __('ui.new_emails') }}</span>
                        </div>
                        <div class="email-input-container">
                            <div class="email-input-group">
                                <input type="email" id="email-input" placeholder="{{ __('ui.email') }}" class="email-input">
                                <button type="button" onclick="addEmail()" class="add-email-btn">{{ __('ui.add') }}</button>
                            </div>
                        </div>
                    </div>

                    <div class="invite-section" data-section="previous">
                        <div class="section-heading">
                            <div>
                                <h3>{{ __('ui.previous_invitees') }}</h3>
                                <p class="invite-section-subtitle">{{ __('ui.previous_invitees_intro') }}</p>
                            </div>
                            <span class="pill neutral">{{ __('ui.history') }}</span>
                        </div>
                        <div class="search-container">
                            <div class="search-input-wrapper">
                                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <input type="text" id="search-invitees" placeholder="{{ __('ui.search_participant') }}" class="search-input">
                            </div>
                        </div>
                        <div id="invitees-list" class="invitees-list"></div>
                    </div>

                    <div class="invite-section" data-section="groups">
                        <div class="section-heading">
                            <div>
                                <h3>{{ __('ui.groups_title') }}</h3>
                                <p class="invite-section-subtitle">{{ __('ui.groups_intro') }}</p>
                            </div>
                            <span class="pill accent">{{ __('ui.team') }}</span>
                        </div>
                        <div class="search-container group-search-container">
                            <div class="search-input-wrapper">
                                <svg class="search-icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
                                <input type="text" id="search-groups" placeholder="{{ __('ui.groups_title') }}" class="search-input">
                            </div>
                        </div>
                        <div id="groups-list" class="groups-list grid"></div>
                    </div>

                    <section class="selected-recipients-card" aria-live="polite">
                        <div class="selected-recipients-header">
                            <h3>{{ __('ui.selected_recipients') }}</h3>
                            <span class="selected-recipients-chip" id="selected-recipients-chip">0</span>
                        </div>
                        <p class="selected-recipients-subtitle">{{ __('ui.selected_recipients_intro') }}</p>
                        <div id="email-list" class="email-list"></div>
                    </section>
                    
                    <div class="invite-footer">
                        <div class="invite-footer-meta">
                            <span class="invite-count" id="invite-count-label">{{ __('ui.no_recipients_selected') }}</span>
                            <span class="invite-feedback" id="invite-feedback" aria-live="polite"></span>
                        </div>
                        <button type="button" onclick="sendInvitations()" class="btn primary-btn" id="send-invitations-btn" disabled>{{ __('ui.send_invitations') }}</button>
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

        function openVolunteerConfirm(mode) {
            var modal = document.getElementById('volunteer-confirm-modal');
            if (!modal) return;
            var title = document.getElementById('volunteer-confirm-title');
            var text = document.getElementById('volunteer-confirm-text');
            var joinForm = document.getElementById('volunteer-join-form');
            var leaveForm = document.getElementById('volunteer-leave-form');
            if (joinForm) joinForm.style.display = 'none';
            if (leaveForm) leaveForm.style.display = 'none';

            if (mode === 'leave') {
                if (title) title.textContent = '{{ __('ui.stop_volunteer_title') }}';
                if (text) text.textContent = 'Du bliver sat tilbage som almindelig deltager. Er du sikker på, at du vil fortsætte?';
                if (leaveForm) leaveForm.style.display = 'inline-flex';
            } else {
                if (title) title.textContent = '{{ __('ui.confirm_volunteer_title') }}';
                if (text) text.textContent = '{{ __('ui.confirm_volunteer_text') }}';
                if (joinForm) joinForm.style.display = 'inline-flex';
            }

            modal.style.display = 'flex';
        }

        function closeVolunteerConfirm() {
            var modal = document.getElementById('volunteer-confirm-modal');
            if (modal) modal.style.display = 'none';
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
            document.getElementById('search-groups').value = '';
            addedEmails = [];
            setInviteFeedback('');
            updateEmailList();
            renderInvitees();
            renderGroups();
        }

        function setInviteFeedback(message = '', tone = 'neutral') {
            const feedback = document.getElementById('invite-feedback');
            if (!feedback) return;
            feedback.textContent = message;
            feedback.classList.remove('is-neutral', 'is-success', 'is-warning');
            if (!message) return;
            if (tone === 'success') feedback.classList.add('is-success');
            else if (tone === 'warning') feedback.classList.add('is-warning');
            else feedback.classList.add('is-neutral');
        }

        function addEmail() {
            const emailInput = document.getElementById('email-input');
            const email = emailInput.value.trim();
            if (!email) {
                setInviteFeedback('{{ __('ui.enter_email_prompt') }}', 'warning');
                return;
            }
            if (!isValidEmail(email)) {
                setInviteFeedback('{{ __('ui.invalid_email') }}', 'warning');
                return;
            }
            if (addedEmails.includes(email)) {
                setInviteFeedback('{{ __('ui.email_already_selected') }}', 'warning');
                return;
            }

            if (email && isValidEmail(email) && !addedEmails.includes(email)) {
                addedEmails.push(email);
                emailInput.value = '';
                setInviteFeedback('{{ __('ui.recipient_added') }}', 'success');
                updateEmailList();
            }
        }

        function removeEmail(email) {
            addedEmails = addedEmails.filter(e => e !== email);
            updateEmailList();
        }

        function updateEmailList() {
            const emailList = document.getElementById('email-list');
            const selectedChip = document.getElementById('selected-recipients-chip');
            if (!emailList) return;

            emailList.innerHTML = '';
            if (addedEmails.length === 0) {
                emailList.innerHTML = '<div class="email-list-empty">{{ __('ui.no_recipients_yet') }}</div>';
            }

            addedEmails.forEach(email => {
                const emailTag = document.createElement('div');
                emailTag.className = 'email-tag';
                const emailText = document.createElement('span');
                emailText.textContent = email;
                const removeButton = document.createElement('button');
                removeButton.type = 'button';
                removeButton.className = 'remove-email-btn';
                removeButton.setAttribute('aria-label', '{{ __('ui.delete') }} ' + email);
                removeButton.innerHTML = '<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="18" y1="6" x2="6" y2="18"></line><line x1="6" y1="6" x2="18" y2="18"></line></svg>';
                removeButton.addEventListener('click', function() { removeEmail(email); });
                emailTag.appendChild(emailText);
                emailTag.appendChild(removeButton);
                emailList.appendChild(emailTag);
            });

            const countLabel = document.getElementById('invite-count-label');
            const sendBtn = document.getElementById('send-invitations-btn');
            const count = addedEmails.length;
            if (selectedChip) {
                selectedChip.textContent = String(count);
            }
            if (count === 0) {
                if (countLabel) countLabel.textContent = '{{ __('ui.no_recipients_selected') }}';
            } else if (count === 1) {
                if (countLabel) countLabel.textContent = '{{ __('ui.one_recipient_ready') }}';
            } else {
                if (countLabel) countLabel.textContent = '{{ __('ui.many_recipients_ready') }}'.replace(':count', String(count));
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
                groupsList.innerHTML = '<div class="group-card empty">{{ __('ui.no_groups_match') }}</div>';
                return;
            }
            matches.forEach(group => {
                const card = document.createElement('div');
                card.className = 'group-card';
                card.innerHTML = `
                    <div class="group-avatar" style="background:${stringToColor(group.name)}">${group.name.charAt(0)}</div>
                    <div class="group-meta">
                        <div class="group-name">${group.name}</div>
                        <div class="group-sub">${group.members ?? 0} {{ __('ui.groups') }}</div>
                    </div>
                    <button type="button" class="group-action" title="{{ __('ui.add_all_group_members') }}">{{ __('ui.add') }}</button>
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
                let addedCount = 0;
                (members || []).forEach(m => {
                    if (m.email && !addedEmails.includes(m.email)) {
                        addedEmails.push(m.email);
                        addedCount++;
                    }
                });
                if (addedCount > 0) {
                    setInviteFeedback(addedCount + ' {{ __('ui.recipient_added_previous') }}', 'success');
                } else {
                    setInviteFeedback('{{ __('ui.recipient_already_selected') }}', 'neutral');
                }
                updateEmailList();
            } catch (e) {
                console.error('Fejl ved tilføjelse af gruppe til invitationer', e);
                setInviteFeedback('{{ __('ui.could_not_fetch_invitees') }}', 'warning');
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
            inviteesList.innerHTML = '<div class="invitee-item">{{ __('ui.fetching_invitees') }}</div>';
            try {
                const res = await fetch('{{ route('events.invitees', $event->id) }}', { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
                if (!res.ok) throw new Error('Failed');
                previousInvitees = await res.json() || [];
                renderInvitees();
            } catch (e) {
                inviteesList.innerHTML = '<div class="invitee-item">{{ __('ui.could_not_fetch_invitees') }}</div>';
            }
        }

        function renderInvitees(filter = '') {
            const inviteesList = document.getElementById('invitees-list');
            const normalized = filter.trim().toLowerCase();
            const items = (previousInvitees || [])
                .filter(i => ((i.name || '').toLowerCase().includes(normalized) || (i.email || '').toLowerCase().includes(normalized)))
                .slice(0, 3);
            if (!items.length) {
                    inviteesList.innerHTML = '<div class="invitee-item">{{ __('ui.no_previous_invitees') }}</div>';
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
                            <span class="invitee-name">${i.name || '{{ __('ui.unknown') }}'}</span>
                            <span class="invitee-email">${i.email}</span>
                        </div>
                    </div>
                    <button type="button" class="invitee-select-btn">{{ __('ui.select') }}</button>
                `;
                item.querySelector('.invitee-select-btn').addEventListener('click', () => selectInvitee(i.email));
                inviteesList.appendChild(item);
            });
        }

        function selectInvitee(email) {
            if (!addedEmails.includes(email)) {
                addedEmails.push(email);
                setInviteFeedback('{{ __('ui.recipient_added_previous') }}', 'success');
                updateEmailList();
            } else {
                setInviteFeedback('{{ __('ui.recipient_already_selected') }}', 'neutral');
            }
        }

        function sendInvitations() {
            const form = document.querySelector('#invite-modal form');
            if (!form) return;
            if (!addedEmails.length) {
                setInviteFeedback('{{ __('ui.select_at_least_one') }}', 'warning');
                return;
            }

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
                sendBtn.textContent = '{{ __('ui.sending_invitations') }}';
            }
            setInviteFeedback('{{ __('ui.sending_invitations') }}', 'neutral');

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

        var volunteerModalEl = document.getElementById('volunteer-confirm-modal');
        if (volunteerModalEl) {
            volunteerModalEl.addEventListener('click', function(e) {
                if (e.target === this) { closeVolunteerConfirm(); }
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


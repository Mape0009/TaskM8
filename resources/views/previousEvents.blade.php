<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = 'TaskM8 | ' . __('ui.previous_events_page');
        $metaDescription = __('ui.previous_events_lead');
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/participants-modal.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'previousEvents'])

    <main class="main-content-full">
        <div class="overview-shell">
            @php
                \Carbon\Carbon::setLocale('da');
                $pastEventsCount = ($previousEvents ?? collect())->count();
                $myPastEventsCount = (auth()->check() && isset($participant))
                    ? collect($participant)->where('userId', auth()->id())->unique('eventId')->count()
                    : 0;
            @endphp
            <section class="overview-hero">
                <div class="hero-copy">
                    <p class="eyebrow">{{ __('ui.previous_events_page') }}</p>
                    <h1>{{ __('ui.previous_events_hero') }}</h1>
                    <p class="lede">
                        {{ __('ui.previous_events_lead') }}
                    </p>
                    <div class="hero-meta">
                        @auth
                        @else
                            <span class="pill pill-muted">{{ __('ui.login_to_view_previous') }}</span>
                        @endauth
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ url('/dashboard?open=create') }}" class="btn create-btn">{{ __('ui.plan_new_event') }}</a>
                    <a href="{{ url('/events') }}" class="btn secondary-ghost">{{ __('ui.back_to_active') }}</a>
                </div>
            </section>

            <section class="previous-events-listing">
                <h2>{{ __('ui.past_events') }}</h2>
                <div class="event-list">
                    @forelse($previousEvents ?? [] as $event)
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
                                @endphp
                                @if($isOwnerMenu)
                                <div class="event-kebab rsvp-menu" id="prev-event-menu-{{ $event->id }}">
                                    <button class="kebab-btn rsvp-menu-trigger" onclick="toggleRsvpDropdown('prev-event-menu-{{ $event->id }}')" aria-label="{{ __('ui.open_menu') }}">
                                        <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="1"></circle><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="17" r="1"></circle></svg>
                                    </button>
                                    <div class="rsvp-menu-list" role="menu" style="right:0; min-width: 200px;">
                                        <a class="rsvp-menu-item" href="/events/{{ $event->id }}?open=delete">{{ __('ui.delete_event') }}</a>
                                    </div>
                                </div>
                                @endif
                            </div>
                            @php
                                $end = $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
                            @endphp
                            <p class="event-description">{{ __('ui.previous_event_ended', ['date' => $end ? $end->format('j.n.Y') . ' kl ' . $end->format('H:i') : '-']) }}</p>
                            <div class="event-actions">
                                <button type="button" class="btn secondary-btn" onclick="openTemplateModal({{ $event->id }}, '{{ addslashes($event->eventName) }}')">{{ __('ui.use_as_template') }}</button>
                                <button type="button" class="btn secondary-btn" onclick="openParticipantsModal({{ $event->id }}, '{{ $event->eventName }}')">{{ __('ui.participants') }}</button>
                                <a href="/events/{{ $event->id }}" class="btn primary-btn">{{ __('ui.details') }}</a>
                            </div>
                        </div>
                    @empty
                        <p>{{ __('ui.no_previous_events') }}</p>
                    @endforelse
                </div>
            </section>
        </div>
    </main>
    @include('partials.footer')
    <script src="{{ asset('js/participants-modal.js') }}"></script>
    <script>
        function toggleRsvpDropdown(id) {
            var m = document.getElementById(id);
            if (!m) return;
            var isOpen = m.classList.contains('open');
            document.querySelectorAll('.rsvp-menu.open').forEach(function(el){ el.classList.remove('open'); });
            if (!isOpen) m.classList.add('open');
        }

        function openTemplateModal(eventId, eventName) {
            var m = document.getElementById('template-modal');
            if (!m) return;
            m.style.display = 'flex';
            m.setAttribute('data-event-id', String(eventId));
            var title = document.getElementById('template-modal-title');
            if (title) { title.textContent = '{{ __('ui.template_title') }} – ' + eventName; }
        }
        function closeTemplateModal() {
            var m = document.getElementById('template-modal');
            if (m) m.style.display = 'none';
        }
        function chooseTemplateKeep(keep) {
            var m = document.getElementById('template-modal');
            if (!m) return;
            var eventId = m.getAttribute('data-event-id');
            if (!eventId) return;
            if (keep) {
                localStorage.setItem('template_keep_members_event_id', String(eventId));
            } else {
                localStorage.removeItem('template_keep_members_event_id');
                localStorage.removeItem('template_keep_members_payload');
            }
            const url = new URL(window.location.origin + '/dashboard');
            url.searchParams.set('open', 'create');
            url.searchParams.set('templateEventId', String(eventId));
            window.location.href = url.toString();
        }
        document.addEventListener('click', function(e){
            var m = document.getElementById('template-modal');
            if (m && e.target === m) { closeTemplateModal(); }
        });
    </script>
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
                        <h2 id="participants-modal-title">{{ __('ui.participants_modal_title') }}</h2>
                        <p class="participants-modal-subtitle" id="participants-modal-subtitle">{{ __('ui.participants_modal_subtitle') }}</p>
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
                    <input type="text" id="participants-search" class="participants-search-input" placeholder="{{ __('ui.search_participant') }}">
                    <div class="participants-categories">
                        <button type="button" class="participants-category-btn active" data-category="all">
                            {{ __('ui.all') }} <span class="count" id="count-all">0</span>
                        </button>
                        <button type="button" class="participants-category-btn" data-category="accepted">
                            {{ __('ui.attending') }} <span class="count" id="count-accepted">0</span>
                        </button>
                        <button type="button" class="participants-category-btn" data-category="declined">
                            {{ __('ui.not_attending') }} <span class="count" id="count-declined">0</span>
                        </button>
                        <button type="button" class="participants-category-btn" data-category="pending">
                            {{ __('ui.awaiting_response') }} <span class="count" id="count-pending">0</span>
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
                            {{ __('ui.loading_participants') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Template Choice Modal -->
    <div id="template-modal" class="participants-modal" role="dialog" aria-modal="true" aria-labelledby="template-modal-title" style="display:none;">
        <div class="participants-modal-content">
            <div class="participants-modal-header">
                <div class="participants-modal-header-content">
                    <div class="participants-modal-icon">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" viewBox="0 0 24 24">
                            <rect x="3" y="4" width="18" height="14" rx="2" ry="2"></rect>
                            <line x1="8" y1="2" x2="8" y2="6"></line>
                            <line x1="16" y1="2" x2="16" y2="6"></line>
                        </svg>
                    </div>
                    <div class="participants-modal-title">
                        <h2 id="template-modal-title">{{ __('ui.template_title') }}</h2>
                        <p class="participants-modal-subtitle">{{ __('ui.template_keep_question') }}</p>
                    </div>
                </div>
                <button class="participants-modal-close-btn" onclick="closeTemplateModal()" aria-label="Luk">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="18" y1="6" x2="6" y2="18"></line>
                        <line x1="6" y1="6" x2="18" y2="18"></line>
                    </svg>
                </button>
            </div>
            <div class="participants-modal-body" style="padding: 24px;">
                <div class="confirm-actions" style="display:flex; gap:12px; justify-content:flex-end;">
                    <button type="button" class="btn secondary-btn" onclick="chooseTemplateKeep(false)">Nej, start uden medlemmer</button>
                    <button type="button" class="btn primary-btn" onclick="chooseTemplateKeep(true)">Ja, behold medlemmer</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html> 


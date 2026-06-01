<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = 'TaskM8 | ' . __('ui.page_title_dashboard');
        $metaDescription = __('ui.guest_subtitle');
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TaskM8 {{ __('ui.page_title_dashboard') }}</title>
    <link rel="stylesheet" href="{{ asset('css/participants-modal.css') }}">
</head>
<body>
    @include('partials.header', ['currentPage' => 'dashboard'])

    <main class="main-content-full">
        @auth
        <section class="stats-cards">
            <div class="stat-card stat-card--pending">
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><circle cx="12" cy="12" r="10"></circle><path d="M12 6v6l4 2"></path></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{$pendingEventsCount}}</span>
                    <span class="stat-title">{{ __('ui.stat_pending') }}</span>
                    <span class="stat-note">{{ __('ui.stat_pending_note') }}</span>
                </div>
            </div>
            <div class="stat-card stat-card--mine">
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{$participatedEventsCount}}</span>
                    <span class="stat-title">{{ __('ui.stat_my_events') }}</span>
                    <span class="stat-note">{{ __('ui.stat_my_events_note') }}</span>
                </div>
            </div>
            <div class="stat-card stat-card--network">
                <div class="stat-icon">
                    <svg class="icon" fill="none" stroke="currentColor" viewBox="0 0 24 24" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493 M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07 M15 19.128v.106A12.318 12.318 0 0 1 8.624 21 c-2.331 0-4.512-.645-6.374-1.766l-.001-.109 a6.375 6.375 0 0 1 11.964-3.07 M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25 a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z" />
                    </svg>
                </div>
                <div class="stat-info">
                    <span class="stat-value">{{ $previousInviteesCount }}</span>
                    <span class="stat-title">{{ __('ui.stat_previous_invitees') }}</span>
                    <span class="stat-note">{{ __('ui.stat_previous_invitees_note') }}</span>
                </div>
            </div>
        </section>
        <section class="upcoming-events">
            <h2>{{ __('ui.upcoming_events') }}</h2>
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
                                <button class="kebab-btn rsvp-menu-trigger" onclick="toggleRsvpDropdown('event-menu-{{ $event->id }}')" aria-label="{{ __('ui.open_menu') }}">
                                    <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="7" r="1"></circle><circle cx="12" cy="12" r="1"></circle><circle cx="12" cy="17" r="1"></circle></svg>
                                </button>
                                <div class="rsvp-menu-list" role="menu" style="right:0; min-width: 200px;">
                                    @auth
                                        @if($canCreateTask)
                                            <a class="rsvp-menu-item" href="{{ route('events.tasks.create.form', ['eventId' => $event->id]) }}">{{ __('ui.create_task') }}</a>
                                        @endif
                                        @if($canViewTask)
                                            <a class="rsvp-menu-item" href="{{ route('events.tasks.index', ['eventId' => $event->id]) }}">{{ __('ui.tasks') }}</a>
                                        @endif
                                        @if($canInvite)
                                            <a class="rsvp-menu-item" href="/events/{{ $event->id }}?open=invite">{{ __('ui.invite') }}</a>
                                        @endif
                                        @if($canManageAnyRole)
                                            <a class="rsvp-menu-item" href="{{ route('events.participants', ['eventId' => $event->id]) }}">{{ __('ui.assign_roles') }}</a>
                                        @endif
                                        @if($canEditEvent)
                                            <a class="rsvp-menu-item" href="/events/{{ $event->id }}/edit">{{ __('ui.edit_event') }}</a>
                                        @endif
                                        @if($isOwnerMenu)
                                            <a class="rsvp-menu-item" href="/events/{{ $event->id }}?open=delete">{{ __('ui.delete_event') }}</a>
                                        @endif
                                    @endauth
                                </div>
                            </div>
                            @endif
                        </div>
                        <p class="event-description">{{ Str::limit($event->description, 25) }}</p>
                       <div class="event-actions">
    <a href="/events/{{ $event->id }}" class="btn primary-btn event-main-action">{{ __('ui.details') }}</a>
<a href="javascript:void(0);" 
   class="btn secondary-btn event-main-action" 
   onclick="openParticipantsModal({{ $event->id }}, '{{ $event->eventName }}')">
    {{ __('ui.participants') }}
</a>
    @auth
        @php
            $isOwner = false; 
            foreach ($participant as $p) {
                if ($p->userId === auth()->id() && $event->id === $p->eventId && $p->eventRole === 'owner') {
                    $isOwner = true;
                    break;
                }
            }
            $myParticipation = \App\Models\EventParticipant::where('eventId', $event->id)->where('userId', auth()->id())->first();
            $rsvpStatus = $myParticipation->status ?? null; 
        @endphp
        @if(!$isOwner)
            {{-- Slet-knap --}}
            <button type="button" class="bin-button" aria-label="Forlad begivenhed" 
                    onclick="document.getElementById('leave-modal-{{ $event->id }}').style.display='flex'">
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

            {{-- RSVP-status --}}
            {{-- <div class="rsvp-status {{ $rsvpStatus === 'accepted' ? 'accepted' : ($rsvpStatus === 'declined' ? 'declined' : 'pending') }}">
                @if($rsvpStatus === 'accepted')
                    <span class="status-dot"></span> Deltager
                @elseif($rsvpStatus === 'declined')
                    <span class="status-dot"></span> Deltager ikke
                @else
                    <span class="status-dot"></span> Afventer svar
                @endif
            </div> --}}

            {{-- Leave/Delete modals --}}
            <div id="leave-modal-{{ $event->id }}" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="leave-confirm-title-{{ $event->id }}" style="display:none;">
                <div class="confirm-modal-content">
                    <div class="confirm-modal-body">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                            <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
                        </svg>
                        <h2 id="leave-confirm-title-{{ $event->id }}" class="confirm-title">{{ __('ui.confirm_are_you_sure') }}</h2>
                        <p class="confirm-text">{{ __('ui.confirm_leave_event') }}</p>
                    </div>
                    <div class="confirm-actions">
                        <button type="button" class="confirm-btn cancel" onclick="document.getElementById('leave-modal-{{ $event->id }}').style.display='none'">{{ __('ui.cancel') }}</button>
                        <form action="{{ route('events.decline', ['eventId' => $event->id]) }}" method="POST" style="display:inline;">
                            @csrf
                            <button type="submit" class="confirm-btn danger">{{ __('ui.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>

            @auth
            @if(isset($event->ownerId) && $event->ownerId === auth()->id())
            <div id="delete-modal-{{ $event->id }}" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="delete-confirm-title-{{ $event->id }}" style="display:none;">
                <div class="confirm-modal-content">
                    <div class="confirm-modal-body">
                        <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg"><path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path></svg>
                        <h2 id="delete-confirm-title-{{ $event->id }}" class="confirm-title">{{ __('ui.delete_event') }}?</h2>
                        <p class="confirm-text">{{ __('ui.confirm_delete_event') }}</p>
                    </div>
                    <div class="confirm-actions">
                        <button type="button" class="confirm-btn cancel" onclick="document.getElementById('delete-modal-{{ $event->id }}').style.display='none'">{{ __('ui.cancel') }}</button>
                        <form action="{{ url('/events/delete/'.$event->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="confirm-btn danger">{{ __('ui.delete') }}</button>
                        </form>
                    </div>
                </div>
            </div>
            @endif
            @endauth
        @endif
    @endauth
</div>
                    </div>
                @empty
                    <p>{{ __('ui.no_events_found') }}</p>
                @endforelse
            </div>
        </section>
        @endauth

        @guest
        <div class="guest-landing">
        <section class="guest-hero">
            <div class="guest-hero__grid">
                <div class="guest-hero__content">
                    <p class="guest-hero__eyebrow animate-from-top">{{ __('ui.guest_eyebrow') }}</p>
                    <h1 class="guest-hero__title animate-from-top">{{ __('ui.guest_title') }}</h1>
                    <p class="guest-hero__subtitle animate-from-left delay-150">{{ __('ui.guest_subtitle') }}

</p>
                    <div class="guest-hero__cta animate-from-right delay-300">
                        <a href="/signup" class="btn primary-btn guest-hero__cta-btn">{{ __('ui.get_started') }}</a>
                        <a href="/signin" class="btn secondary-btn guest-hero__cta-btn guest-hero__cta-btn--secondary">{{ __('ui.login') }}</a>
                    </div>
                </div>

                <aside class="guest-hero__panel animate-fade-up" aria-label="{{ __('ui.overview') }}">
                    <div class="guest-hero__panel-top">
                        <span class="guest-hero__panel-dot"></span>
                        <span class="guest-hero__panel-dot"></span>
                        <span class="guest-hero__panel-dot"></span>
                    </div>
                    <div class="guest-hero__metrics">
                        <div class="metric animate-scale-in"><div class="metric__value">{{ $totalUsers }}</div><div class="metric__label">{{ __('ui.total_users') }}</div></div>
                        <div class="metric animate-scale-in delay-2"><div class="metric__value">{{ $totalEvents }}</div><div class="metric__label">{{ __('ui.total_events') }}</div></div>
                    </div>
                    <div class="guest-hero__list">
                        <div class="guest-hero__list-item">
                            <span class="guest-hero__list-icon"></span>
                            <span>{{ __('ui.invite_with_click') }}</span>
                        </div>
                        <div class="guest-hero__list-item">
                            <span class="guest-hero__list-icon"></span>
                            <span>{{ __('ui.see_responses_live') }}</span>
                        </div>
                        <div class="guest-hero__list-item">
                            <span class="guest-hero__list-icon"></span>
                            <span>{{ __('ui.manage_tasks') }}</span>
                        </div>
                    </div>
                </aside>
            </div>
        </section>

        <section class="features">
            <h2 class="animate-fade-up">{{ __('ui.features') }}</h2>
            <div class="features-grid">
                <div class="feature-card animate-fade-up">
                    <div class="feature-card__icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="4" width="18" height="18" rx="2"/><line x1="16" y1="2" x2="16" y2="6"/><line x1="8" y1="2" x2="8" y2="6"/><line x1="3" y1="10" x2="21" y2="10"/></svg>
                    </div>
                    <h3 class="feature-card__title">{{ __('ui.feature_create_title') }}</h3>
                    <p class="feature-card__text">{{ __('ui.feature_create_text') }}</p>
                </div>
                <div class="feature-card animate-fade-up delay-2">
                    <div class="feature-card__icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><circle cx="12" cy="12" r="10"/><path d="M12 6v6l4 2"/></svg>
                    </div>
                    <h3 class="feature-card__title">{{ __('ui.feature_realtime_title') }}</h3>
                    <p class="feature-card__text">{{ __('ui.feature_realtime_text') }}</p>
                </div>
                <div class="feature-card animate-fade-up delay-3">
                    <div class="feature-card__icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 6h18M3 12h18M3 18h18"/></svg>
                    </div>
                    <h3 class="feature-card__title">{{ __('ui.feature_roles_title') }}</h3>
                    <p class="feature-card__text">{{ __('ui.feature_roles_text') }}</p>
                </div>
                <div class="feature-card animate-fade-up delay-4">
                    <div class="feature-card__icon">
                        <svg class="icon" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" stroke-linejoin="round" d="M10.5 1.5H8.25A2.25 2.25 0 0 0 6 3.75v16.5a2.25 2.25 0 0 0 2.25 2.25h7.5A2.25 2.25 0 0 0 18 20.25V3.75a2.25 2.25 0 0 0-2.25-2.25H13.5m-3 0V3h3V1.5m-3 0h3m-3 18.75h3" /> </svg>
                    </div>
                    <h3 class="feature-card__title">{{ __('ui.feature_devices_title') }}</h3>
                    <p class="feature-card__text">{{ __('ui.feature_devices_text') }}</p>
                </div>
            </div>
        </section>

        {{-- <section class="cta-banner animate-fade-up">
            <div class="cta-banner__content">
                <h3>Download vores app</h3>
                <p>TaskM8 er tilgængelig på både iOS og Android.</p>
            </div>
            <div class="store-badges">
                <a class="store-badge store-badge--apple" href="#" aria-label="Download på App Store">
                    <svg class="store-badge__icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M447.1 332.7C446.9 296 463.5 268.3 497.1 247.9C478.3 221 449.9 206.2 412.4 203.3C376.9 200.5 338.1 224 323.9 224C308.9 224 274.5 204.3 247.5 204.3C191.7 205.2 132.4 248.8 132.4 337.5C132.4 363.7 137.2 390.8 146.8 418.7C159.6 455.4 205.8 545.4 254 543.9C279.2 543.3 297 526 329.8 526C361.6 526 378.1 543.9 406.2 543.9C454.8 543.2 496.6 461.4 508.8 424.6C443.6 393.9 447.1 334.6 447.1 332.7zM390.5 168.5C417.8 136.1 415.3 106.6 414.5 96C390.4 97.4 362.5 112.4 346.6 130.9C329.1 150.7 318.8 175.2 321 202.8C347.1 204.8 370.9 191.4 390.5 168.5z"/></svg>
                    <div class="store-badge__text">
                        <span class="store-badge__small">Hent i</span>
                        <span class="store-badge__big">App Store</span>
                    </div>
                </a>
                <a class="store-badge store-badge--google" href="#" aria-label="Hent den på Google Play">
                    <svg class="store-badge__icon" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640"><path d="M389.6 298.3L168.9 77L449.7 238.2L389.6 298.3zM111.3 64C98.3 70.8 89.6 83.2 89.6 99.3L89.6 540.6C89.6 556.7 98.3 569.1 111.3 575.9L367.9 319.9L111.3 64zM536.5 289.6L477.6 255.5L411.9 320L477.6 384.5L537.7 350.4C555.7 336.1 555.7 303.9 536.5 289.6zM168.9 563L449.7 401.8L389.6 341.7L168.9 563z"/></svg>
                    <div class="store-badge__text">
                        <span class="store-badge__small">Hent i</span>
                        <span class="store-badge__big">Google Play</span>
                    </div>
                </a>
            </div>
        </section>
        </div> --}}
        @endguest
    </main>
    <script src="{{ asset('build/assets/app-DNxiirP_.js') }}" type="module"></script>
    @guest
    <script src="https://cdn.jsdelivr.net/npm/@formkit/auto-animate@1.0.0-beta.6/dist/auto-animate.min.js"></script>
    <script src="{{ asset('js/landing.js') }}"></script>
    @endguest
    @include('partials.participants-modal-i18n')
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
                        <h2 id="participants-modal-title">{{ __('ui.participants_modal_title') }}</h2>
                        <p class="participants-modal-subtitle" id="participants-modal-subtitle">{{ __('ui.participants_modal_subtitle') }}</p>
                    </div>
                </div>
                <button class="participants-modal-close-btn" onclick="closeParticipantsModal()" aria-label="{{ __('ui.close') }}">
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
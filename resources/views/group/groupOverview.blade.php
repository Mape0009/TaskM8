<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('ui.group_overview_page_title') }}</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groupOverview.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'groups/overview'])

    <main class="main-content-full">
    <div class="overview-shell">
        <section class="overview-hero">
            <div class="hero-copy">
                <p class="eyebrow">{{ __('ui.groups') }}</p>
                <h1>{{ __('ui.group_overview_title') }}</h1>
                <p class="lede">{{ __('ui.group_overview_intro') }}</p>
                <div class="hero-meta">
                    @unless($isAuthenticated)
                        <span class="pill pill-muted">{{ __('ui.group_overview_login_hint') }}</span>
                    @endunless
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ url('groups/create') }}" class="btn create-btn">{{ __('ui.group_create_btn') }}</a>
            </div>
        </section>

        <section class="filter-bar">
            <div class="segmented-control" role="tablist" aria-label="{{ __('ui.group_overview_switch') }}">
                <button class="segment-btn is-active" data-target="all" role="tab" aria-selected="true">
                    {{ __('ui.group_all') }}
                </button>
                <button class="segment-btn" data-target="mine" role="tab" aria-selected="false" {{ $isAuthenticated ? '' : 'disabled' }}>
                    {{ __('ui.group_mine') }}
                    <span class="segment-count">{{ $myGroups->count() }}</span>
                </button>
            </div>
        </section>

        <section class="groups-section">
            <div class="groups-grid" data-view="all">
                @forelse ($allGroups as $group)
                    <article class="group-card">
                        <div class="group-card-header">
                            <div>
                                <p class="card-eyebrow">{{ $group->private ? __('ui.group_private_group') : __('ui.group_public_group') }}</p>
                                <h2>{{ $group->groupName }}</h2>
                            </div>
                            <span class="group-private {{ $group->private ? 'private' : 'public' }}">
                                {{ $group->private ? __('ui.group_private') : __('ui.group_public') }}
                            </span>
                        </div>
                        <p class="group-description">{{ $group->description ?? __('ui.group_no_description') }}</p>
                        <div class="group-card-footer">
                            <div class="meta">
                                <span class="meta-item">
                                    <span class="dot"></span>
                                    {{ __('ui.group_created') }} {{ optional($group->created_at)->format('d.m.Y') ?? '—' }}
                                </span>
                            </div>
                            @auth
                                <div class="group-actions">
                                    @if($group->ownerId === auth()->id())
                                        <a href="{{ route('groups.manage', $group->id) }}" class="btn secondary-btn">
                                            <span>{{ __('ui.group_manage_members') }}</span>
                                        </a>
                                        <button type="button"
                                            class="btn delete-btn"
                                                onclick="openGroupConfirmModal('{{ route('groups.delete', ['id' => $group->id]) }}', '{{ __('ui.group_delete_title') }}', '{{ __('ui.group_delete_confirm') }}', 'DELETE')">
                                                {{ __('ui.delete') }}
                                        </button>
                                    @elseif($myGroupIds->contains($group->id))
                                        <button type="button"
                                            class="btn secondary-btn"
                                            onclick="openGroupConfirmModal('{{ route('groups.leave', ['id' => $group->id]) }}', '{{ __('ui.group_leave_title') }}', '{{ __('ui.group_leave_confirm') }}', 'POST')">
                                            {{ __('ui.group_leave') }}
                                        </button>
                                    @elseif(!$group->private)
                                        <form action="{{ route('groups.join', ['id' => $group->id]) }}" method="POST" class="delete-form">
                                            @csrf
                                            <button type="submit" class="btn primary-btn">{{ __('ui.group_join') }}</button>
                                        </form>
                                    @endif
                                </div>
                            @endauth
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                            <p class="empty-title">{{ __('ui.group_none_title') }}</p>
                            <p class="empty-text">{{ __('ui.group_none_text') }}</p>
                    </div>
                @endforelse
            </div>

            <div class="groups-grid is-hidden" data-view="mine">
                @if(!$isAuthenticated)
                    <div class="empty-state">
                            <p class="empty-title">{{ __('ui.group_login_title') }}</p>
                            <p class="empty-text">{{ __('ui.group_login_text') }}</p>
                    </div>
                @else
                    @forelse ($myGroups as $group)
                        <article class="group-card">
                            <div class="group-card-header">
                                <div>
                                    <p class="card-eyebrow">{{ __('ui.group_mine') }}</p>
                                    <h2>{{ $group->groupName }}</h2>
                                </div>
                                <span class="group-private {{ $group->private ? 'private' : 'public' }}">
                                    {{ $group->private ? __('ui.group_private') : __('ui.group_public') }}
                                </span>
                            </div>
                            <p class="group-description">{{ $group->description ?? __('ui.group_no_description') }}</p>
                            <div class="group-card-footer">
                                <div class="meta">
                                    <span class="meta-item">
                                        <span class="dot"></span>
                                        {{ __('ui.group_member_since') }} {{ optional($group->created_at)->format('d.m.Y') ?? '—' }}
                                    </span>
                                </div>
                                @auth
                                    <div class="group-actions">
                                        @if($group->ownerId === auth()->id())
                                            <a href="{{ route('groups.manage', $group->id) }}" class="btn secondary-btn">
                                                <span>{{ __('ui.group_manage_members') }}</span>
                                            </a>
                                            <button type="button"
                                                class="btn delete-btn"
                                                onclick="openGroupConfirmModal('{{ route('groups.delete', ['id' => $group->id]) }}', '{{ __('ui.group_delete_title') }}', '{{ __('ui.group_delete_confirm') }}', 'DELETE')">
                                                Slet
                                            </button>
                                        @elseif($myGroupIds->contains($group->id))
                                            <button type="button"
                                                class="btn secondary-btn"
                                                onclick="openGroupConfirmModal('{{ route('groups.leave', ['id' => $group->id]) }}', '{{ __('ui.group_leave_title') }}', '{{ __('ui.group_leave_confirm') }}', 'POST')">
                                                {{ __('ui.group_leave') }}
                                            </button>
                                        @elseif(!$group->private)
                                            <form action="{{ route('groups.join', ['id' => $group->id]) }}" method="POST" class="delete-form">
                                                @csrf
                                                <button type="submit" class="btn primary-btn">{{ __('ui.group_join') }}</button>
                                            </form>
                                        @endif
                                    </div>
                                @endauth
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                                <p class="empty-title">{{ __('ui.group_none_member_title') }}</p>
                                <p class="empty-text">{{ __('ui.group_none_member_text') }}</p>
                        </div>
                    @endforelse
                @endif
            </div>
        </section>
    </div>
    </main>

    @include('partials.footer')

    {{-- Bekræftelsesmodal til slet/forlad gruppe --}}
    <div id="group-confirm-modal" class="confirm-modal" style="display:none;">
        <div class="confirm-modal-content">
            <div class="confirm-modal-body">
                <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                    <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
                </svg>
                    <h2 id="group-confirm-title" class="confirm-title">{{ __('ui.confirm_are_you_sure') }}</h2>
                    <p id="group-confirm-text" class="confirm-text">{{ __('ui.group_confirm_action') }}</p>
            </div>
            <div class="confirm-actions">
                <button type="button" class="confirm-btn cancel" onclick="closeGroupConfirmModal()">{{ __('ui.cancel') }}</button>
                    <form id="group-confirm-form" method="POST" class="confirm-btn-form">
                    @csrf
                    <input type="hidden" name="_method" id="group-confirm-method" value="POST">
                        <button type="submit" class="confirm-btn danger">{{ __('ui.confirm') }}</button>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const buttons = Array.from(document.querySelectorAll('.segment-btn'));
            const grids = Array.from(document.querySelectorAll('.groups-grid'));

            buttons.forEach((btn) => {
                btn.addEventListener('click', () => {
                    if (btn.disabled) return;

                    const target = btn.dataset.target;

                    buttons.forEach((b) => {
                        b.classList.toggle('is-active', b === btn);
                        b.setAttribute('aria-selected', b === btn ? 'true' : 'false');
                    });

                    grids.forEach((grid) => {
                        grid.classList.toggle('is-hidden', grid.dataset.view !== target);
                    });
                });
            });
        });

        function openGroupConfirmModal(actionUrl, title, text, method = 'POST') {
            const modal = document.getElementById('group-confirm-modal');
            const form = document.getElementById('group-confirm-form');
            const titleEl = document.getElementById('group-confirm-title');
            const textEl = document.getElementById('group-confirm-text');
            const methodInput = document.getElementById('group-confirm-method');

            if (!modal || !form || !titleEl || !textEl || !methodInput) return;

            form.action = actionUrl;
            titleEl.textContent = title;
            textEl.textContent = text;
            methodInput.value = method;

            modal.style.display = 'flex';
        }

        function closeGroupConfirmModal() {
            const modal = document.getElementById('group-confirm-modal');
            if (modal) modal.style.display = 'none';
        }

        // Luk modal ved klik på overlay
        (function attachOverlayClose() {
            const modal = document.getElementById('group-confirm-modal');
            if (!modal) return;
            modal.addEventListener('click', (e) => {
                if (e.target === modal) {
                    closeGroupConfirmModal();
                }
            });
        })();
    </script>
</body>
</html>

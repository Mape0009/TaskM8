<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = __('ui.group_manage_page_title');
        $metaDescription = __('ui.group_manage_meta', ['group' => ($group->groupName ?? __('ui.group'))]);
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <link rel="stylesheet" href="{{ asset('css/groupOverview.css') }}?v={{ filemtime(public_path('css/groupOverview.css')) }}">
</head>
<body class="manage-members-page">
    @include('partials.header', ['currentPage' => 'groups/overview'])

    <main class="main-content-full">
        <div class="overview-shell">
            <section class="overview-hero">
                <div class="hero-copy">
                    <p class="eyebrow">{{ __('ui.group_admin') }}</p>
                    <h1>{{ $group->groupName }}</h1>
                    <p class="lede">
                        {{ __('ui.group_manage_intro') }}
                    </p>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('groups.overview') }}" class="btn create-btn">{{ __('ui.back_to_groups') }}</a>
                </div>
            </section>

            <section class="groups-section manage-members-section">
                <div class="groups-grid manage-members-grid">
                    <article class="group-card manage-members-card">
                        <div class="group-card-header">
                            <div>
                                <p class="card-eyebrow">{{ __('ui.group_add_member') }}</p>
                                <h2>{{ __('ui.group_invite_by_email') }}</h2>
                            </div>
                            <span class="group-private {{ $group->private ? 'private' : 'public' }}">
                                {{ $group->private ? __('ui.group_private_group') : __('ui.group_public_group') }}
                            </span>
                        </div>
                        <p class="group-description">
                            {{ __('ui.group_manage_helper') }}
                        </p>
                        <form action="{{ route('groups.members.add', $group->id) }}" method="POST" class="group-form manage-members-form">
                            @csrf
                            <div class="form-row">
                                <label for="member-email">{{ __('ui.email') }}</label>
                                <input type="email" id="member-email" name="email" placeholder="{{ __('ui.group_enter_email') }}" required>
                            </div>
                            <div class="form-actions manage-members-form-actions">
                                <button type="submit" class="btn primary-btn">{{ __('ui.group_add_member_btn') }}</button>
                            </div>
                        </form>
                    </article>

                    <article class="group-card manage-members-card">
                        <div class="group-card-header">
                            <div>
                                <p class="card-eyebrow">{{ __('ui.group_members') }}</p>
                                <h2>{{ __('ui.group_current_members') }}</h2>
                            </div>
                            <span class="group-private manage-members-count">
                                {{ $members->count() }} {{ $members->count() === 1 ? __('ui.group_member') : __('ui.group_members') }}
                            </span>
                        </div>

                        @if($members->isEmpty())
                            <div class="empty-state manage-members-empty-state">
                                <p class="empty-title">{{ __('ui.group_no_members_title') }}</p>
                                <p class="empty-text">{{ __('ui.group_no_members_text') }}</p>
                            </div>
                        @else
                            <div class="members-list manage-members-list">
                                @foreach ($members as $member)
                                    <div class="member-row manage-member-row">
                                        <div class="manage-member-main">
                                            <div class="group-avatar manage-member-avatar">
                                                {{ strtoupper(substr($member->user?->name ?? $member->user?->email ?? '?', 0, 1)) }}
                                            </div>
                                            <div class="manage-member-meta">
                                                <span class="manage-member-name">
                                                    {{ $member->user?->name ?? __('ui.unknown_user') }}
                                                </span>
                                                <span class="manage-member-email">
                                                    {{ $member->user?->email ?? __('ui.no_email') }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="manage-member-actions">
                                            <span class="pill manage-member-since">
                                                {{ __('ui.group_member_since') }} {{ optional($member->created_at)->format('d.m.Y') ?? '—' }}
                                            </span>
                                            @if($member->userId === $group->ownerId)
                                                <span class="pill neutral manage-owner-pill">{{ __('ui.owner') }}</span>
                                            @else
                                                <form action="{{ route('groups.members.remove', [$group->id, $member->id]) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn delete-btn">{{ __('ui.remove') }}</button>
                                                </form>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </article>
                </div>
            </section>
        </div>
    </main>

    @include('partials.footer')
</body>
</html>

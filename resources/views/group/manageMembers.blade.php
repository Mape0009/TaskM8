<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Administrer gruppemedlemmer';
        $metaDescription = 'Administrer medlemmer i gruppen ' . ($group->groupName ?? 'gruppe') . ' på TaskM8.';
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
                    <p class="eyebrow">Gruppeadministration</p>
                    <h1>{{ $group->groupName }}</h1>
                    <p class="lede">
                        Tilføj eller fjern medlemmer i gruppen. Du er ejer af denne gruppe, og ændringer træder i kraft med det samme.
                    </p>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('groups.overview') }}" class="btn create-btn">Tilbage til grupper</a>
                </div>
            </section>

            <section class="groups-section manage-members-section">
                <div class="groups-grid manage-members-grid">
                    <article class="group-card manage-members-card">
                        <div class="group-card-header">
                            <div>
                                <p class="card-eyebrow">Tilføj medlem</p>
                                <h2>Invitér via e-mail</h2>
                            </div>
                            <span class="group-private {{ $group->private ? 'private' : 'public' }}">
                                {{ $group->private ? 'Privat gruppe' : 'Offentlig gruppe' }}
                            </span>
                        </div>
                        <p class="group-description">
                            Skriv e-mailen på en bruger, der allerede har en TaskM8-konto, for at tilføje vedkommende som medlem.
                        </p>
                        <form action="{{ route('groups.members.add', $group->id) }}" method="POST" class="group-form manage-members-form">
                            @csrf
                            <div class="form-row">
                                <label for="member-email">E-mailadresse</label>
                                <input type="email" id="member-email" name="email" placeholder="person@example.com" required>
                            </div>
                            <div class="form-actions manage-members-form-actions">
                                <button type="submit" class="btn primary-btn">Tilføj medlem</button>
                            </div>
                        </form>
                    </article>

                    <article class="group-card manage-members-card">
                        <div class="group-card-header">
                            <div>
                                <p class="card-eyebrow">Medlemmer</p>
                                <h2>Aktuelle gruppemedlemmer</h2>
                            </div>
                            <span class="group-private manage-members-count">
                                {{ $members->count() }} medlem{{ $members->count() === 1 ? '' : 'mer' }}
                            </span>
                        </div>

                        @if($members->isEmpty())
                            <div class="empty-state manage-members-empty-state">
                                <p class="empty-title">Ingen medlemmer endnu</p>
                                <p class="empty-text">Tilføj dit første medlem via formularen til venstre.</p>
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
                                                    {{ $member->user?->name ?? 'Ukendt bruger' }}
                                                </span>
                                                <span class="manage-member-email">
                                                    {{ $member->user?->email ?? 'Ingen e-mail' }}
                                                </span>
                                            </div>
                                        </div>
                                        <div class="manage-member-actions">
                                            <span class="pill manage-member-since">
                                                Medlem siden {{ optional($member->created_at)->format('d.m.Y') ?? '—' }}
                                            </span>
                                            @if($member->userId === $group->ownerId)
                                                <span class="pill neutral manage-owner-pill">Ejer</span>
                                            @else
                                                <form action="{{ route('groups.members.remove', [$group->id, $member->id]) }}" method="POST" class="delete-form">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="btn delete-btn">Fjern</button>
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

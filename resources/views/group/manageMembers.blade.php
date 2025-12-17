<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administrer medlemmer | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groupOverview.css') }}">
</head>
<body>
    @include('partials.header', ['currentPage' => 'groups/overview'])

    <main class="overview-shell">
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

        <section class="groups-section" style="margin-top: 1.75rem;">
            <div class="groups-grid manage-members-grid">
                <!-- Venstre kolonne: Tilføj medlem -->
                <article class="group-card">
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
                    <form action="{{ route('groups.members.add', $group->id) }}" method="POST" class="group-form" style="margin-top: 0.75rem;">
                        @csrf
                        <div class="form-row">
                            <label for="member-email">E-mailadresse</label>
                            <p class="helper"></p>
                            <input type="email" id="member-email" name="email" placeholder="person@example.com" required>
                        </div>
                        <div class="form-actions" style="margin-top: 1rem; display:flex; justify-content:flex-end; gap:0.5rem;">
                            <button type="submit" class="btn primary-btn">Tilføj medlem</button>
                        </div>
                    </form>
                </article>

                <!-- Højre kolonne: Medlemsliste -->
                <article class="group-card">
                    <div class="group-card-header">
                        <div>
                            <p class="card-eyebrow">Medlemmer</p>
                            <h2>Aktuelle gruppemedlemmer</h2>
                        </div>
                        <span class="group-private">
                            {{ $members->count() }} medlem{{ $members->count() === 1 ? '' : 'mer' }}
                        </span>
                    </div>

                    @if($members->isEmpty())
                        <div class="empty-state" style="margin-top: 0.75rem;">
                            <p class="empty-title">Ingen medlemmer endnu</p>
                            <p class="empty-text">Tilføj dit første medlem via formularen til venstre.</p>
                        </div>
                    @else
                        <div class="members-list" style="margin-top: 0.75rem; display:flex; flex-direction:column; gap:0.75rem;">
                            @foreach ($members as $member)
                                <div class="member-row" style="display:flex; align-items:center; justify-content:space-between; gap:0.75rem; padding:0.75rem 0; border-bottom:1px solid var(--color-border, #e2e8f0);">
                                    <div style="display:flex; align-items:center; gap:0.75rem;">
                                        <div class="group-avatar" style="width:32px; height:32px; border-radius:999px; background:#e5e7eb; display:flex; align-items:center; justify-content:center; font-weight:700; color:#111827;">
                                            {{ strtoupper(substr($member->user?->name ?? $member->user?->email ?? '?', 0, 1)) }}
                                        </div>
                                        <div style="display:flex; flex-direction:column;">
                                            <span style="font-weight:600; color:var(--color-text-primary, #0f172a);">
                                                {{ $member->user?->name ?? 'Ukendt bruger' }}
                                            </span>
                                            <span style="font-size:0.85rem; color:var(--color-text-secondary, #64748b);">
                                                {{ $member->user?->email ?? 'Ingen e-mail' }}
                                            </span>
                                        </div>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:0.5rem;">
                                        <span class="pill" style="font-size:0.75rem;">
                                            Medlem siden {{ optional($member->created_at)->format('d.m.Y') ?? '—' }}
                                        </span>
                                        @if($member->userId === $group->ownerId)
                                            <span class="pill neutral" style="background:#eff6ff; color:#1d4ed8; border:none;">Ejer</span>
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
    </main>
</body>
</html>

<!DOCTYPE html>
<html lang="da">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gruppeoversigt | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/groupoverview.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    @include('partials.header', ['currentPage' => 'groups/overview'])

    <main class="overview-shell">
        <section class="overview-hero">
            <div class="hero-copy">
                <p class="eyebrow">Grupper</p>
                <h1>Få overblik over dine fællesskaber</h1>
                <p class="lede">
                    Saml teams, frivillige og samarbejdspartnere ét sted. Skift mellem alle åbne grupper og dem du allerede er med i.
                </p>
                <div class="hero-meta">
                    <span class="pill">Alle grupper: {{ $allGroups->count() }}</span>
                    <span class="pill">Mine grupper: {{ $myGroups->count() }}</span>
                    @unless($isAuthenticated)
                        <span class="pill pill-muted">Log ind for at se dine grupper</span>
                    @endunless
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ url('groups/create') }}" class="btn create-btn">Opret gruppe</a>
            </div>
        </section>

        <section class="filter-bar">
            <div class="segmented-control" role="tablist" aria-label="Skift gruppeliste">
                <button class="segment-btn is-active" data-target="all" role="tab" aria-selected="true">
                    Alle grupper
                </button>
                <button class="segment-btn" data-target="mine" role="tab" aria-selected="false" {{ $isAuthenticated ? '' : 'disabled' }}>
                    Mine grupper
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
                                <p class="card-eyebrow">Gruppe</p>
                                <h2>{{ $group->groupName }}</h2>
                            </div>
                            <span class="group-private {{ $group->private ? 'private' : 'public' }}">
                                {{ $group->private ? 'Privat' : 'Offentlig' }}
                            </span>
                        </div>
                        <p class="group-description">{{ $group->description ?? 'Ingen beskrivelse tilføjet endnu.' }}</p>
                        <div class="group-card-footer">
                            <div class="meta">
                                <span class="meta-item">
                                    <span class="dot"></span>
                                    Oprettet {{ optional($group->created_at)->format('d.m.Y') ?? '—' }}
                                </span>
                            </div>
                            <form action="{{ route('groups.delete', ['id' => $group->id]) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn delete-btn">Slet</button>
                            </form>
                        </div>
                    </article>
                @empty
                    <div class="empty-state">
                        <p class="empty-title">Der er ingen grupper endnu</p>
                        <p class="empty-text">Opret en ny gruppe for at komme i gang.</p>
                    </div>
                @endforelse
            </div>

            <div class="groups-grid is-hidden" data-view="mine">
                @if(!$isAuthenticated)
                    <div class="empty-state">
                        <p class="empty-title">Log ind for at se dine grupper</p>
                        <p class="empty-text">Når du er logget ind, viser vi de grupper du ejer eller allerede er medlem af.</p>
                    </div>
                @else
                    @forelse ($myGroups as $group)
                        <article class="group-card">
                            <div class="group-card-header">
                                <div>
                                    <p class="card-eyebrow">Mine grupper</p>
                                    <h2>{{ $group->groupName }}</h2>
                                </div>
                                <span class="group-private {{ $group->private ? 'private' : 'public' }}">
                                    {{ $group->private ? 'Privat' : 'Offentlig' }}
                                </span>
                            </div>
                            <p class="group-description">{{ $group->description ?? 'Ingen beskrivelse tilføjet endnu.' }}</p>
                            <div class="group-card-footer">
                                <div class="meta">
                                    <span class="meta-item">
                                        <span class="dot"></span>
                                        Medlem siden {{ optional($group->created_at)->format('d.m.Y') ?? '—' }}
                                    </span>
                                </div>
                                <form action="{{ route('groups.delete', ['id' => $group->id]) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn delete-btn">Slet</button>
                                </form>
                            </div>
                        </article>
                    @empty
                        <div class="empty-state">
                            <p class="empty-title">Du er ikke i nogen grupper endnu</p>
                            <p class="empty-text">Opret en gruppe eller bliv inviteret for at se dine grupper her.</p>
                        </div>
                    @endforelse
                @endif
            </div>
        </section>
    </main>

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
    </script>
</body>
</html>

<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Opret opgave';
        $metaDescription = 'Opret og tildel opgaver til begivenheder i TaskM8.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shifts-create.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body class="task-create-page">
@include('partials.header', ['currentPage' => 'tasks'])
<main class="main-content-full">
    <div class="overview-shell">
        <section class="overview-hero">
            <div class="hero-copy">
                <p class="eyebrow">Ny opgave</p>
                <h1>Opret opgave{{ isset($event) ? ' til ' . $event->eventName : '' }}</h1>
                <p class="lede">Brug den samme enkle 3-trins guide som i vagt-oprettelse: navngiv opgaven, beskriv den og bekræft.</p>
                <div class="hero-meta">
                    <span class="pill">Trin: 3</span>
                    <span class="pill">Deltagere: {{ $users->count() }}</span>
                    @if(isset($event))
                        <span class="pill">Begivenhed: {{ $event->eventName }}</span>
                    @endif
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ url()->previous() }}" class="btn secondary-ghost">Tilbage</a>
            </div>
        </section>

        <section class="shift-create-layout">
            <article class="task-form-wrapper">
                @if($errors->any())
                    <div class="alert alert-error" role="alert">
                        <ul>
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="shift-wizard-progress" aria-label="Trin i oprettelse af opgave">
                    <div class="shift-wizard-step is-active" data-step-indicator="1">1. Navn</div>
                    <div class="shift-wizard-step" data-step-indicator="2">2. Beskrivelse</div>
                    <div class="shift-wizard-step" data-step-indicator="3">3. Bekræft</div>
                </div>

                <form action="{{ isset($event) ? route('events.tasks.create', ['eventId' => $event->id]) : route('task.create') }}" method="POST" class="task-form" id="taskWizard" novalidate>
                    @csrf

                    <div class="form-step" data-step="1">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Opgave navn</h3>
                                <p>Giv opgaven et kort og tydeligt navn</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taskName" class="form-label">Opgave navn</label>
                            <input type="text" id="taskName" name="taskName" class="form-input" placeholder="Fx Indkøb" value="{{ old('taskName') }}" required>
                        </div>
                        <div class="form-actions form-actions-end">
                            <button type="button" class="btn primary-btn" data-next>Fortsæt</button>
                        </div>
                    </div>

                    <div class="form-step" data-step="2" hidden>
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Beskrivelse</h3>
                                <p>Beskriv kort hvad der skal laves</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">Beskrivelse</label>
                            <div style="position: relative;">
                                <textarea id="description" name="description" class="form-input" placeholder="Beskriv opgaven" maxlength="500" rows="5">{{ old('description') }}</textarea>
                                <span id="desc-counter" style="position: absolute; bottom: 8px; right: 12px; font-size: 12px; color: var(--color-text-secondary);">0/500</span>
                            </div>
                        </div>
                        <div class="form-actions form-actions-split">
                            <button type="button" class="btn secondary-btn" data-prev>Tilbage</button>
                            <button type="button" class="btn primary-btn" data-next>Fortsæt</button>
                        </div>
                    </div>

                    <div class="form-step" data-step="3" hidden>
                        <div class="step-header">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>Bekræft</h3>
                                <p>Tjek oplysningerne inden du opretter opgaven</p>
                            </div>
                        </div>
                        <div class="form-group review-card">
                            <div class="review-grid">
                                <div>
                                    <div class="review-label">Opgave</div>
                                    <div id="reviewTaskName" class="review-value">-</div>
                                </div>
                                <div>
                                    <div class="review-label">Beskrivelse</div>
                                    <div id="reviewDescription" class="review-value">-</div>
                                </div>
                                @if(isset($event))
                                <div>
                                    <div class="review-label">Begivenhed</div>
                                    <div class="review-value">{{ $event->eventName }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-actions form-actions-split">
                            <button type="button" class="btn secondary-btn" data-prev>Tilbage</button>
                            <button type="submit" class="btn primary-btn">Lav opgave</button>
                        </div>
                    </div>
                </form>
            </article>

            <aside class="shift-create-aside">
                <div class="edit-card">
                    <h3>Tip til skarpe opgaver</h3>
                    <p id="task-step-tip-text">Brug et konkret navn og en tydelig beskrivelse, så deltagere hurtigt forstår hvad de skal levere.</p>

                    <div class="task-edit-meta-item">
                        <span id="task-step-tip-label">Anbefaling</span>
                        <strong id="task-step-tip-value">1 opgave = 1 tydeligt ansvar</strong>
                    </div>

                    @if(isset($event))
                    <div class="task-edit-meta-item">
                        <span>Begivenhed</span>
                        <strong>{{ $event->eventName }}</strong>
                    </div>
                    @endif

                    <div class="task-edit-meta-item">
                        <span>Flow</span>
                        <strong>3 trin fra ide til oprettet opgave</strong>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

@include('partials.footer')
<script src="{{ asset('js/task-create.js') }}"></script>
</body>
</html>


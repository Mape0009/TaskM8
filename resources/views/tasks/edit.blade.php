<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'TaskM8 | Rediger opgave';
        $metaDescription = 'Opdater opgaveinformation og tilknytning i TaskM8.';
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
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
</head>
<body class="task-edit-page">
@include('partials.header', ['currentPage' => 'tasks'])
<main class="main-content-full">
    <div class="overview-shell">
        <section class="overview-hero">
            <div class="hero-copy">
                <p class="eyebrow">Rediger opgave</p>
                <h1>{{ $tasks->taskName }}</h1>
                <p class="lede">Opdater titel og beskrivelse, så opgaven er tydelig og nem at overdrage.</p>
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

                <div class="shift-wizard-progress" aria-label="Trin i redigering af opgave">
                    <div class="shift-wizard-step is-active" data-step-indicator="1">1. Opgave</div>
                    <div class="shift-wizard-step" data-step-indicator="2">2. Detaljer</div>
                    <div class="shift-wizard-step" data-step-indicator="3">3. Bekræft</div>
                </div>

                <form action="{{ route('task.update', ['id' => $tasks->id]) }}" method="POST" class="task-form" id="taskEditWizard" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="form-step" data-step="1">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Opgave</h3>
                                <p>Opdater navn og gør opgaven let at forstå</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="taskName" class="form-label">Opgavenavn</label>
                            <input type="text" id="taskName" name="taskName" class="form-input" value="{{ old('taskName', $tasks->taskName) }}" maxlength="255" required placeholder="Fx Klargøring af lokale">
                            @error('taskName')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions form-actions-end">
                            <button type="button" class="btn primary-btn" data-next>Fortsæt</button>
                        </div>
                    </div>

                    <div class="form-step" data-step="2" hidden>
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Detaljer</h3>
                                <p>Juster beskrivelse, så opgaven er let at udføre</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">Beskrivelse</label>
                            <div class="textarea-wrap">
                                <textarea id="description" name="description" class="form-input" rows="5" maxlength="800" placeholder="Skriv hvad opgaven indeholder, og hvad der forventes af personen.">{{ old('description', $tasks->description) }}</textarea>
                                <span class="counter" id="task-description-counter">0 / 800</span>
                            </div>
                            @error('description')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
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
                                <p>Gennemgå ændringerne før du gemmer</p>
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
                            </div>
                        </div>

                        <div class="form-actions form-actions-split task-edit-actions">
                            <a href="{{ url()->previous() }}" class="btn secondary-btn">Annuller</a>
                            <div class="form-actions-inline">
                                <button type="button" class="btn secondary-btn" data-prev>Tilbage</button>
                                <button type="submit" class="btn primary-btn">Gem ændringer</button>
                            </div>
                        </div>
                    </div>
                </form>
            </article>

            <aside class="shift-create-aside">
                <div class="edit-card">
                    <h3>Tip til redigering</h3>
                    <p id="task-edit-tip-text">Start med et konkret navn, så opgaven er let at finde i listen.</p>

                    <div class="task-edit-meta-item">
                        <span id="task-edit-tip-label">Navn</span>
                        <strong id="task-edit-tip-value">Brug 3-6 ord med tydeligt ansvar</strong>
                    </div>

                    @if(isset($event))
                        <div class="task-edit-meta-item">
                            <span>Begivenhed</span>
                            <strong>{{ $event->eventName }}</strong>
                        </div>
                    @endif

                    <div class="task-edit-meta-item">
                        <span>Fokus</span>
                        <strong>En opgave skal beskrive ansvar, ikke arbejdstid</strong>
                    </div>
                </div>
            </aside>
        </section>
    </div>
</main>

@include('partials.footer')

<script src="{{ asset('js/task-edit.js') }}"></script>

</body>
</html>



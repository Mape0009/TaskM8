<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = 'Opret opgave | TaskM8';
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
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@include('partials.header', ['currentPage' => 'tasks'])
<div class="edit-container">
    <div class="edit-header" style="display: flex; align-items: center; justify-content: space-between;">
        <h1 class="edit-title">Lav ny opgave</h1>
        @if(isset($event))
        <p class="muted" style="margin-left: 0.5rem;">Til begivenhed: <strong>{{ $event->eventName }}</strong></p>
        @endif
        <a href="{{ url()->previous() }}" class="btn white-btn">Tilbage</a>
    </div>

    <div class="task-form-wrapper">
        <form action="{{ isset($event) ? route('events.tasks.create', ['eventId' => $event->id]) : route('task.create') }}" method="POST" class="edit-form task-form" id="taskWizard" novalidate>
            @csrf

            <!-- Trin 1: Opgave Navn -->
            <div class="form-step" data-step="1">
                <div class="step-header">
                    <div class="step-number">1</div>
                    <div class="step-content">
                        <h3>Opgave Navn</h3>
                        <p>Giv opgaven et beskrivende navn</p>
                    </div>
                </div>
                <div class="form-row">
                    <label for="taskName">Opgave Navn *</label>
                    <input type="text" id="taskName" name="taskName" placeholder="Opgave Navn" required>
                </div>
                <div class="form-actions" style="display:flex;justify-content:flex-end;gap:12px">
                    <button type="button" class="btn primary-btn" data-next>Næste</button>
                </div>
            </div>

            <!-- Trin 2: Beskrivelse -->
            <div class="form-step" data-step="2" hidden>
                <div class="step-header">
                    <div class="step-number">2</div>
                    <div class="step-content">
                        <h3>Beskrivelse</h3>
                        <p>Beskriv hvad opgaven indeholder</p>
                    </div>
                </div>
                <div class="form-row">
                    <label for="description">Beskrivelse</label>
                    <div style="position: relative;">
                        <textarea id="description" name="description" placeholder="Opgave Beskrivelse" maxlength="500" rows="4"></textarea>
                        <span id="desc-counter" style="position: absolute; bottom: 6px; right: 8px; font-size: 12px; color: var(--text-muted, #6b7280);">0/500</span>
                    </div>
                </div>
                <div class="form-actions" style="display:flex;justify-content:space-between;gap:12px">
                    <button type="button" class="btn secondary-btn" data-prev>Tilbage</button>
                    <button type="button" class="btn primary-btn" data-next>Næste</button>
                </div>
            </div>

            <!-- Trin 3: Gennemse og opret -->
            <div class="form-step" data-step="3" hidden>
                <div class="step-header">
                    <div class="step-number">3</div>
                    <div class="step-content">
                        <h3>Gennemse</h3>
                        <p>Tjek at alt ser rigtigt ud</p>
                    </div>
                </div>
                <div class="form-row">
                    <label>Opgave</label>
                    <div id="reviewTaskName" class="muted" style="font-weight:700"></div>
                </div>
                <div class="form-row">
                    <label>Beskrivelse</label>
                    <div id="reviewDescription" class="muted"></div>
                </div>
                @if(isset($event))
                <div class="form-row">
                    <label>Begivenhed</label>
                    <div class="muted"><strong>{{ $event->eventName }}</strong></div>
                </div>
                @endif
                <div class="form-actions" style="display:flex;justify-content:space-between;gap:12px">
                    <button type="button" class="btn secondary-btn" data-prev>Tilbage</button>
                    <button type="submit" class="btn primary-btn">Lav Opgave</button>
                </div>
            </div>
        </form>
    </div>
</div>
<script src="{{ asset('js/task-create.js') }}"></script>

<style>
    /* Form Steps */
    .form-step {
        background: var(--color-background-primary);
        border-radius: var(--radius-lg);
        padding: calc(var(--spacing-unit) * 4);
        margin-bottom: calc(var(--spacing-unit) * 3);
        box-shadow: var(--shadow-sm);
        transition: all var(--transition-smooth);
    }

    .form-step:hover {
        box-shadow: var(--shadow-md);
    }

    .step-header {
        display: flex;
        align-items: center;
        gap: calc(var(--spacing-unit) * 3);
        margin-bottom: calc(var(--spacing-unit) * 3);
        padding-bottom: calc(var(--spacing-unit) * 2);
        border-bottom: 2px solid var(--color-border);
    }

    .step-number {
        width: calc(var(--spacing-unit) * 6);
        height: calc(var(--spacing-unit) * 6);
        background: var(--color-accent-primary);
        color: #ffffff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: calc(var(--spacing-unit) * 2.5);
        font-weight: 700;
        flex-shrink: 0;
    }

    .step-content h3 {
        font-size: calc(var(--spacing-unit) * 3);
        font-weight: 600;
        color: var(--color-text-primary);
        margin: 0 0 calc(var(--spacing-unit) * 0.5) 0;
    }

    .step-content p {
        color: var(--color-text-secondary);
        font-size: calc(var(--spacing-unit) * 2);
        margin: 0;
    }

    /* Responsive Design */
    @media (max-width: 768px) {
        .step-header {
            flex-direction: column;
            text-align: center;
            gap: calc(var(--spacing-unit) * 2);
        }
    }
</style>
</body>
</html>


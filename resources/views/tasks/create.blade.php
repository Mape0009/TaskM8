<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $pageTitle = __('ui.task_create_page_title');
        $metaDescription = __('ui.task_create_meta');
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
<body class="task-create-page">
@include('partials.header', ['currentPage' => 'tasks'])
<main class="main-content-full">
    <div class="overview-shell">
        <section class="overview-hero">
            <div class="hero-copy">
                <p class="eyebrow">{{ __('ui.task_create_eyebrow') }}</p>
                <h1>
                    @if(isset($event))
                        {{ __('ui.task_create_h1_for', ['event' => $event->eventName]) }}
                    @else
                        {{ __('ui.create_task') }}
                    @endif
                </h1>
                <p class="lede">{{ __('ui.task_create_lede') }}</p>
                <div class="hero-meta">
                    <span class="pill">{{ __('ui.steps_label', ['count' => 3]) }}</span>
                    <span class="pill">{{ __('ui.participants') }}: {{ $users->count() }}</span>
                    @if(isset($event))
                        <span class="pill">{{ __('ui.event_pill', ['name' => $event->eventName]) }}</span>
                    @endif
                </div>
            </div>
            <div class="hero-actions">
                <a href="{{ url()->previous() }}" class="btn secondary-ghost">{{ __('ui.back') }}</a>
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

                <div class="shift-wizard-progress" aria-label="{{ __('ui.task_wizard_aria') }}">
                    <div class="shift-wizard-step is-active" data-step-indicator="1">1. {{ __('ui.wizard_step_name') }}</div>
                    <div class="shift-wizard-step" data-step-indicator="2">2. {{ __('ui.wizard_step_description') }}</div>
                    <div class="shift-wizard-step" data-step-indicator="3">3. {{ __('ui.wizard_step_confirm') }}</div>
                </div>

                <form action="{{ isset($event) ? route('events.tasks.create', ['eventId' => $event->id]) : route('task.create') }}" method="POST" class="task-form" id="taskWizard" novalidate>
                    @csrf

                    <div class="form-step" data-step="1">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                    <h3>{{ __('ui.task_name') }}</h3>
                                    <p>{{ __('ui.task_name_helper', ['example' => __('ui.placeholder_task_name')]) }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="taskName" class="form-label">{{ __('ui.task_name') }}</label>
                            <input type="text" id="taskName" name="taskName" class="form-input" placeholder="{{ __('ui.placeholder_task_name') }}" value="{{ old('taskName') }}" required>
                        </div>
                        <div class="form-actions form-actions-end">
                            <button type="button" class="btn primary-btn" data-next>{{ __('ui.continue') }}</button>
                        </div>
                    </div>

                    <div class="form-step" data-step="2" hidden>
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>{{ __('ui.description') }}</h3>
                                <p>{{ __('ui.describe_task_short') }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="description" class="form-label">{{ __('ui.description') }}</label>
                            <div style="position: relative;">
                                <textarea id="description" name="description" class="form-input" placeholder="{{ __('ui.placeholder_description') }}" maxlength="500" rows="5">{{ old('description') }}</textarea>
                                <span id="desc-counter" style="position: absolute; bottom: 8px; right: 12px; font-size: 12px; color: var(--color-text-secondary);">0/500</span>
                            </div>
                        </div>
                        <div class="form-actions form-actions-split">
                            <button type="button" class="btn secondary-btn" data-prev>{{ __('ui.back') }}</button>
                            <button type="button" class="btn primary-btn" data-next>{{ __('ui.continue') }}</button>
                        </div>
                    </div>

                    <div class="form-step" data-step="3" hidden>
                        <div class="step-header">
                            <div class="step-number">3</div>
                            <div class="step-content">
                                <h3>{{ __('ui.wizard_step_confirm') }}</h3>
                                <p>{{ __('ui.task_confirm_check') }}</p>
                            </div>
                        </div>
                        <div class="form-group review-card">
                            <div class="review-grid">
                                <div>
                                    <div class="review-label">{{ __('ui.review_task_label') }}</div>
                                    <div id="reviewTaskName" class="review-value">-</div>
                                </div>
                                <div>
                                    <div class="review-label">{{ __('ui.review_description_label') }}</div>
                                    <div id="reviewDescription" class="review-value">-</div>
                                </div>
                                @if(isset($event))
                                <div>
                                    <div class="review-label">{{ __('ui.event_label') }}</div>
                                    <div class="review-value">{{ $event->eventName }}</div>
                                </div>
                                @endif
                            </div>
                        </div>
                        <div class="form-actions form-actions-split">
                            <button type="button" class="btn secondary-btn" data-prev>{{ __('ui.back') }}</button>
                            <button type="submit" class="btn primary-btn">{{ __('ui.create_task') }}</button>
                        </div>
                    </div>
                </form>
            </article>

            <aside class="shift-create-aside">
                <div class="edit-card">
                    <h3>{{ __('ui.task_create_tip_title') }}</h3>
                    <p id="task-step-tip-text">{{ __('ui.task_create_tip_text') }}</p>

                    <div class="task-edit-meta-item">
                        <span id="task-step-tip-label">{{ __('ui.recommendation') }}</span>
                        <strong id="task-step-tip-value">{{ __('ui.recommendation_value') }}</strong>
                    </div>

                    @if(isset($event))
                    <div class="task-edit-meta-item">
                        <span>{{ __('ui.event_label') }}</span>
                        <strong>{{ $event->eventName }}</strong>
                    </div>
                    @endif

                    <div class="task-edit-meta-item">
                        <span>{{ __('ui.flow_label') }}</span>
                        <strong>{{ __('ui.flow_value') }}</strong>
                    </div>
                </div>
            </aside>
        </div>
    </div>
</main>

@include('partials.footer')
@php
    $taskCreateTips = [
        [
            'text' => __('ui.task_create_tip_step1_text'),
            'label' => __('ui.task_create_tip_step1_label'),
            'value' => __('ui.task_create_tip_step1_value'),
        ],
        [
            'text' => __('ui.task_create_tip_step2_text'),
            'label' => __('ui.task_create_tip_step2_label'),
            'value' => __('ui.task_create_tip_step2_value'),
        ],
        [
            'text' => __('ui.task_create_tip_step3_text'),
            'label' => __('ui.task_create_tip_step3_label'),
            'value' => __('ui.task_create_tip_step3_value'),
        ],
    ];
@endphp
<script>
    window.taskCreateTips = @json($taskCreateTips);
</script>
<script src="{{ asset('js/task-create.js') }}"></script>
</body>
</html>


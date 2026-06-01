<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    @php
        $pageTitle = __('ui.task_edit_page_title');
        $metaDescription = __('ui.task_edit_meta');
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
                <p class="eyebrow">{{ __('ui.edit_task') }}</p>
                <h1>{{ $tasks->taskName }}</h1>
                <p class="lede">{{ __('ui.task_edit_lede') }}</p>
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

                <div class="shift-wizard-progress" aria-label="{{ __('ui.task_edit_wizard_aria') }}">
                    <div class="shift-wizard-step is-active" data-step-indicator="1">1. {{ __('ui.wizard_step_name') }}</div>
                    <div class="shift-wizard-step" data-step-indicator="2">2. {{ __('ui.wizard_step_description') }}</div>
                    <div class="shift-wizard-step" data-step-indicator="3">3. {{ __('ui.wizard_step_confirm') }}</div>
                </div>

                <form action="{{ route('task.update', ['id' => $tasks->id]) }}" method="POST" class="task-form" id="taskEditWizard" novalidate>
                    @csrf
                    @method('PUT')

                    <div class="form-step" data-step="1">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>{{ __('ui.task_name') }}</h3>
                                <p>{{ __('ui.task_update_helper') }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="taskName" class="form-label">{{ __('ui.task_name') }}</label>
                            <input type="text" id="taskName" name="taskName" class="form-input" value="{{ old('taskName', $tasks->taskName) }}" maxlength="255" required placeholder="{{ __('ui.placeholder_task_name') }}">
                            @error('taskName')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="form-actions form-actions-end">
                            <button type="button" class="btn primary-btn" data-next>{{ __('ui.continue') }}</button>
                        </div>
                    </div>

                    <div class="form-step" data-step="2" hidden>
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>{{ __('ui.details') }}</h3>
                                <p>{{ __('ui.task_details_helper') }}</p>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="description" class="form-label">{{ __('ui.description') }}</label>
                            <div class="textarea-wrap">
                                <textarea id="description" name="description" class="form-input" rows="5" maxlength="800" placeholder="{{ __('ui.placeholder_description_full') }}">{{ old('description', $tasks->description) }}</textarea>
                                <span class="counter" id="task-description-counter">0 / 800</span>
                            </div>
                            @error('description')
                                <p class="field-error">{{ $message }}</p>
                            @enderror
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
                            </div>
                        </div>

                        <div class="form-actions form-actions-split task-edit-actions">
                            <a href="{{ url()->previous() }}" class="btn secondary-btn">{{ __('ui.cancel') }}</a>
                            <div class="form-actions-inline">
                                <button type="button" class="btn secondary-btn" data-prev>{{ __('ui.back') }}</button>
                                <button type="submit" class="btn primary-btn">{{ __('ui.save_changes') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </article>

            <aside class="shift-create-aside">
                <div class="edit-card">
                    <h3>{{ __('ui.task_edit_tip_title') }}</h3>
                    <p id="task-edit-tip-text">{{ __('ui.task_edit_tip_text') }}</p>

                    <div class="task-edit-meta-item">
                        <span id="task-edit-tip-label">{{ __('ui.name_label') }}</span>
                        <strong id="task-edit-tip-value">{{ __('ui.name_value') }}</strong>
                    </div>

                    @if(isset($event))
                        <div class="task-edit-meta-item">
                            <span>{{ __('ui.event_label') }}</span>
                            <strong>{{ $event->eventName }}</strong>
                        </div>
                    @endif

                    <div class="task-edit-meta-item">
                        <span>{{ __('ui.focus_label') }}</span>
                        <strong>{{ __('ui.focus_value') }}</strong>
                    </div>
                </div>
            </aside>
        </section>
    </div>
</main>

@include('partials.footer')

@php
    $taskEditTips = [
        [
            'text' => __('ui.task_edit_tip_step1_text'),
            'label' => __('ui.task_edit_tip_step1_label'),
            'value' => __('ui.task_edit_tip_step1_value'),
        ],
        [
            'text' => __('ui.task_edit_tip_step2_text'),
            'label' => __('ui.task_edit_tip_step2_label'),
            'value' => __('ui.task_edit_tip_step2_value'),
        ],
        [
            'text' => __('ui.task_edit_tip_step3_text'),
            'label' => __('ui.task_edit_tip_step3_label'),
            'value' => __('ui.task_edit_tip_step3_value'),
        ],
    ];
@endphp
<script>
    window.taskEditTips = @json($taskEditTips);
</script>
<script src="{{ asset('js/task-edit.js') }}"></script>

</body>
</html>



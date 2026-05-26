<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = __('ui.event_edit_page_title');
        $metaDescription = __('ui.event_edit_meta');
        $eventStart = \Carbon\Carbon::parse($event->startDate);
        $eventEnd = \Carbon\Carbon::parse($event->endDate);
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
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shifts-create.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
</head>
<body class="task-edit-page event-edit-page">
    @include('partials.header', ['currentPage' => 'events'])
    <main class="main-content-full">
        <div class="overview-shell">
            <section class="overview-hero">
                <div class="hero-copy">
                    <p class="eyebrow">{{ __('ui.edit_event') }}</p>
                    <h1>{{ $event->eventName }}</h1>
                    <p class="lede">{{ __('ui.event_edit_lede') }}</p>
                    <div class="hero-meta">
                        <span class="pill">{{ __('ui.steps_label', ['count' => 3]) }}</span>
                        @if($event->location)
                            <span class="pill pill-muted">{{ $event->location }}</span>
                        @endif
                        <span class="pill pill-muted">{{ $eventStart->format('d/m/Y H:i') }} – {{ $eventEnd->format('H:i') }}</span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ url('/events/'.$event->id) }}" class="btn secondary-ghost">{{ __('ui.cancel') }}</a>
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

                    <div class="shift-wizard-progress" aria-label="{{ __('ui.event_edit_wizard_aria') }}">
                        <div class="shift-wizard-step is-active" data-step-indicator="1">1. {{ __('ui.event_edit_step_basics') }}</div>
                        <div class="shift-wizard-step" data-step-indicator="2">2. {{ __('ui.event_edit_step_schedule') }}</div>
                        <div class="shift-wizard-step" data-step-indicator="3">3. {{ __('ui.wizard_step_confirm') }}</div>
                    </div>

                    <form class="task-form" method="POST" action="{{ route('events.update', ['id' => $event->id]) }}" id="eventEditWizard" novalidate>
                        @csrf
                        @method('PUT')

                        <div class="form-step" data-step="1">
                            <div class="step-header">
                                <div class="step-number">1</div>
                                <div class="step-content">
                                    <h3>{{ __('ui.event_edit_step_basics') }}</h3>
                                    <p>{{ __('ui.event_edit_basics_helper') }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="eventName" class="form-label">{{ __('ui.title') }}</label>
                                <input type="text" id="eventName" name="eventName" class="form-input" value="{{ old('eventName', $event->eventName) }}" required placeholder="{{ __('ui.title') }}">
                                @error('eventName')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="location" class="form-label">{{ __('ui.location') }}</label>
                                <input type="text" id="location" name="location" class="form-input" value="{{ old('location', $event->location) }}" placeholder="{{ __('ui.location') }}">
                                @error('location')
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
                                    <h3>{{ __('ui.event_edit_step_schedule') }}</h3>
                                    <p>{{ __('ui.event_edit_schedule_helper') }}</p>
                                </div>
                            </div>

                            <div class="form-group">
                                <label for="startDate" class="form-label">{{ __('ui.start_time') }}</label>
                                <input type="datetime-local" id="startDate" name="startDate" class="form-input" value="{{ old('startDate', $eventStart->format('Y-m-d\TH:i')) }}" required>
                                @error('startDate')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="endDate" class="form-label">{{ __('ui.end_time') }}</label>
                                <input type="datetime-local" id="endDate" name="endDate" class="form-input" value="{{ old('endDate', $eventEnd->format('Y-m-d\TH:i')) }}" required>
                                @error('endDate')
                                    <p class="field-error">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="form-group">
                                <label for="description" class="form-label">{{ __('ui.description') }}</label>
                                <div class="textarea-wrap">
                                    <textarea id="event-edit-description" name="description" class="form-input" rows="5" maxlength="800" placeholder="{{ __('ui.describe_event') }}">{{ old('description', $event->description) }}</textarea>
                                    <span class="counter" id="event-edit-description-counter">0 / 800</span>
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
                                    <p>{{ __('ui.event_edit_confirm_check') }}</p>
                                </div>
                            </div>

                            <div class="form-group review-card">
                                <div class="review-grid">
                                    <div>
                                        <div class="review-label">{{ __('ui.review_event_name') }}</div>
                                        <div id="reviewEventName" class="review-value">-</div>
                                    </div>
                                    <div>
                                        <div class="review-label">{{ __('ui.review_location_label') }}</div>
                                        <div id="reviewLocation" class="review-value">-</div>
                                    </div>
                                    <div>
                                        <div class="review-label">{{ __('ui.review_schedule_label') }}</div>
                                        <div id="reviewSchedule" class="review-value">-</div>
                                    </div>
                                    <div>
                                        <div class="review-label">{{ __('ui.review_description_label') }}</div>
                                        <div id="reviewDescription" class="review-value">-</div>
                                    </div>
                                </div>
                            </div>

                            <div class="form-actions form-actions-split task-edit-actions">
                                <button type="button" class="btn secondary-btn" data-prev>{{ __('ui.back') }}</button>
                                <button type="submit" class="btn primary-btn">{{ __('ui.save_changes') }}</button>
                            </div>
                        </div>
                    </form>
                </article>

                <aside class="shift-create-aside">
                    <div class="edit-card">
                        <h3>{{ __('ui.event_edit_tip_title') }}</h3>
                        <p id="event-edit-tip-text">{{ __('ui.event_edit_tip_text') }}</p>

                        <div class="task-edit-meta-item">
                            <span id="event-edit-tip-label">{{ __('ui.name_label') }}</span>
                            <strong id="event-edit-tip-value">{{ __('ui.name_value') }}</strong>
                        </div>

                        <div class="task-edit-meta-item">
                            <span>{{ __('ui.event_label') }}</span>
                            <strong>{{ $event->eventName }}</strong>
                        </div>

                        <div class="task-edit-meta-item">
                            <span>{{ __('ui.focus_label') }}</span>
                            <strong>{{ __('ui.event_edit_focus_value') }}</strong>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </main>

    @include('partials.footer')

    @php
        $eventEditTips = [
            [
                'text' => __('ui.event_edit_tip_step1_text'),
                'label' => __('ui.event_edit_tip_step1_label'),
                'value' => __('ui.event_edit_tip_step1_value'),
            ],
            [
                'text' => __('ui.event_edit_tip_step2_text'),
                'label' => __('ui.event_edit_tip_step2_label'),
                'value' => __('ui.event_edit_tip_step2_value'),
            ],
            [
                'text' => __('ui.event_edit_tip_step3_text'),
                'label' => __('ui.event_edit_tip_step3_label'),
                'value' => __('ui.event_edit_tip_step3_value'),
            ],
        ];
    @endphp
    <script>window.eventEditTips = @json($eventEditTips);</script>
    <script src="{{ asset('js/event-edit.js') }}"></script>
</body>
</html>

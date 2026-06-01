<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
<head>
    @php
        $pageTitle = __('ui.shift_create_page_title', ['task' => $task->taskName]);
        $metaDescription = __('ui.shift_create_meta', ['task' => $task->taskName]);
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $pageTitle }}</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shifts-create.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('partials.header', ['currentPage' => 'tasks'])

    <main class="main-content-full">
        <div class="overview-shell">
            <section class="overview-hero">
                <div class="hero-copy">
                    <p class="eyebrow">{{ __('ui.shift_create_eyebrow') }}</p>
                    <h1>{{ __('ui.shift_create_h1_for', ['task' => $task->taskName]) }}</h1>
                    <p class="lede">{{ __('ui.shift_create_lede') }}</p>
                    <div class="hero-meta">
                        <span class="pill">{{ __('ui.task_pill', ['name' => $task->taskName]) }}</span>
                        <span class="pill">{{ __('ui.participants') }}: {{ $users->count() }}</span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn secondary-ghost">{{ __('ui.back_to_shifts') }}</a>
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

                    <div class="shift-wizard-progress" aria-label="{{ __('ui.shift_wizard_aria') }}">
                        <div class="shift-wizard-step is-active" data-step-indicator="1">1. {{ __('ui.wizard_step_person') }}</div>
                        <div class="shift-wizard-step" data-step-indicator="2">2. {{ __('ui.wizard_step_timeslot') }}</div>
                        <div class="shift-wizard-step" data-step-indicator="3">3. {{ __('ui.wizard_step_confirm') }}</div>
                    </div>

                    <form action="{{ route('tasks.shifts.store', $task->id) }}" method="POST" class="task-form" id="shiftWizard" novalidate>
                    @csrf

                    <div class="form-step" data-step="1">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>{{ __('ui.shift_select_person') }}</h3>
                                <p>{{ __('ui.shift_assign_helper') }}</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="userId" class="form-label">{{ __('ui.shift_select_user') }}</label>
                            <select name="userId" id="userId" class="form-select">
                                <option value="">{{ __('ui.no_user_selected') }}</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('userId') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name ?? $user->email ?? __('ui.unknown_user') }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-actions form-actions-end">
                            <button type="button" class="btn primary-btn" data-next>{{ __('ui.continue') }}</button>
                        </div>
                    </div>

                        <div class="form-step" data-step="2" hidden>
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>{{ __('ui.shift_timeslot') }}</h3>
                                <p>{{ __('ui.shift_timeslot_helper') }}</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="startTime" class="form-label">{{ __('ui.shift_start_label') }}</label>
                                <input type="datetime-local" name="startTime" id="startTime" class="form-input" 
                                       value="{{ old('startTime') }}" required
                                       @if($task->event && $task->event->startDate) min="{{ \Carbon\Carbon::parse($task->event->startDate)->format('Y-m-d\TH:i') }}" @endif
                                       @if($task->event && $task->event->endDate) max="{{ \Carbon\Carbon::parse($task->event->endDate)->format('Y-m-d\TH:i') }}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="endTime" class="form-label">{{ __('ui.shift_end_label') }}</label>
                                <input type="datetime-local" name="endTime" id="endTime" class="form-input" 
                                       value="{{ old('endTime') }}" required
                                       @if($task->event && $task->event->startDate) min="{{ \Carbon\Carbon::parse($task->event->startDate)->format('Y-m-d\TH:i') }}" @endif
                                       @if($task->event && $task->event->endDate) max="{{ \Carbon\Carbon::parse($task->event->endDate)->format('Y-m-d\TH:i') }}" @endif>
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
                                <p>{{ __('ui.shift_confirm_helper') }}</p>
                            </div>
                        </div>
                        <div class="form-group review-card">
                            <div class="review-grid">
                                <div>
                                    <div class="review-label">{{ __('ui.review_person_label') }}</div>
                                    <div id="reviewUser" class="review-value"></div>
                                </div>
                                <div>
                                    <div class="review-label">{{ __('ui.review_start_label') }}</div>
                                    <div id="reviewStart" class="review-value"></div>
                                </div>
                                <div>
                                    <div class="review-label">{{ __('ui.review_end_label') }}</div>
                                    <div id="reviewEnd" class="review-value"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions form-actions-split">
                            <div class="form-actions-inline">
                                <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn secondary-btn">{{ __('ui.cancel') }}</a>
                                <button type="submit" class="btn primary-btn"><i class="fas fa-plus"></i>{{ __('ui.create_shift') }}</button>
                            </div>
                        </div>
                    </div>
                </form>
                </article>

                <aside class="shift-create-aside">
                    <div class="edit-card">
                        <h3>{{ __('ui.shift_planning_tip_title') }}</h3>
                        <p>{{ __('ui.shift_planning_tip_text') }}</p>

                        @if($task->event && $task->event->startDate && $task->event->endDate)
                            <div class="task-edit-meta-item">
                                <span>{{ __('ui.event_time_range') }}</span>
                                <strong>
                                    {{ \Carbon\Carbon::parse($task->event->startDate)->format('d.m.Y H:i') }} -
                                    {{ \Carbon\Carbon::parse($task->event->endDate)->format('d.m.Y H:i') }}
                                </strong>
                            </div>
                        @endif

                        <div class="task-edit-meta-item">
                            <span>{{ __('ui.steps_label', ['count' => 3]) }}</span>
                            <strong>{{ __('ui.shift_steps_summary') }}</strong>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </main>

    @include('partials.footer')

    <script src="https://unpkg.com/auto-animate@1.0.2/dist/auto-animate.js"></script>
    <script>
        (function(){
            const form = document.getElementById('shiftWizard');
            if (!form) return;
            const steps = Array.from(form.querySelectorAll('.form-step'));
            const noUserLabel = @json(__('ui.no_user_selected'));
            let current = 0;

            function showStep(index) {
                steps.forEach((s, i) => { s.hidden = i !== index; });
                current = index;
                document.querySelectorAll('[data-step-indicator]').forEach((el, i) => {
                    el.classList.toggle('is-active', i === index);
                    el.classList.toggle('is-done', i < index);
                });
                updateReview();
            }

            function updateReview() {
                const userSel = form.querySelector('#userId');
                const startEl = form.querySelector('#startTime');
                const endEl = form.querySelector('#endTime');
                const userText = userSel && userSel.options[userSel.selectedIndex]
                    ? userSel.options[userSel.selectedIndex].text
                    : noUserLabel;
                const fmt = (v) => v ? new Date(v).toLocaleString([], { year:'numeric', month:'numeric', day:'numeric', hour:'2-digit', minute:'2-digit' }) : '';
                const set = (id, val) => { const el = document.getElementById(id); if (el) el.textContent = val; };
                set('reviewUser', userText);
                set('reviewStart', fmt(startEl && startEl.value));
                set('reviewEnd', fmt(endEl && endEl.value));
            }

            form.addEventListener('click', (e) => {
                const nextBtn = e.target.closest('[data-next]');
                const prevBtn = e.target.closest('[data-prev]');
                if (nextBtn) {
                    const required = steps[current].querySelectorAll('[required]');
                    for (const input of required) {
                        if (!input.value) { input.focus(); return; }
                    }
                    if (current < steps.length - 1) showStep(current + 1);
                }
                if (prevBtn) {
                    if (current > 0) showStep(current - 1);
                }
            });

            const startEl = form.querySelector('#startTime');
            const endEl = form.querySelector('#endTime');
            if (startEl && endEl) {
                startEl.addEventListener('change', function(){
                    const dt = new Date(this.value);
                    if (dt instanceof Date && !isNaN(dt)) {
                        const plus1h = new Date(dt.getTime() + 60*60*1000);
                        const pad = (n) => String(n).padStart(2, '0');
                        const local = `${plus1h.getFullYear()}-${pad(plus1h.getMonth()+1)}-${pad(plus1h.getDate())}T${pad(plus1h.getHours())}:${pad(plus1h.getMinutes())}`;
                        if (!endEl.value || new Date(endEl.value) <= dt) {
                            endEl.value = local;
                        }
                    }
                });
            }

            if (typeof autoAnimate !== 'undefined') {
                autoAnimate(document.querySelector('.task-form-wrapper'));
            }

            showStep(0);
        })();
    </script>
</body>
</html>

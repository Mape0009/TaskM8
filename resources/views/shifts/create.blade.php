<!DOCTYPE html>
<html lang="da" class="no-js">
<head>
    @php
        $pageTitle = 'Tilføj Vagt til ' . $task->taskName . ' | TaskM8';
        $metaDescription = 'Tilføj en ny vagt til opgaven ' . $task->taskName . '.';
    @endphp
    @include('partials.seo', [
        'title' => $pageTitle,
        'description' => $metaDescription,
        'canonical' => url()->current(),
        'image' => asset('TaskM8-Logo.png'),
    ])
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tilføj Vagt til {{ $task->taskName }} | TaskM8</title>
    <link rel="stylesheet" href="{{ asset('css/header.css') }}">
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/design-system.css') }}">
    <link rel="stylesheet" href="{{ asset('css/overview-hero.css') }}">
    <link rel="stylesheet" href="{{ asset('css/shifts-create.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    @include('partials.header', ['currentPage' => 'tasks'])

    <main class="main-content-full">
        <div class="overview-shell">
            <section class="overview-hero">
                <div class="hero-copy">
                    <p class="eyebrow">Ny vagt</p>
                    <h1>Opret vagt for {{ $task->taskName }}</h1>
                    <p class="lede">Følg de tre trin for at tildele en person, vælge tid og bekræfte planen.</p>
                    <div class="hero-meta">
                        <span class="pill">Opgave: {{ $task->taskName }}</span>
                        <span class="pill">Deltagere: {{ $users->count() }}</span>
                    </div>
                </div>
                <div class="hero-actions">
                    <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn secondary-ghost">Tilbage til vagter</a>
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

                    <div class="shift-wizard-progress" aria-label="Trin i oprettelse af vagt">
                        <div class="shift-wizard-step is-active" data-step-indicator="1">1. Person</div>
                        <div class="shift-wizard-step" data-step-indicator="2">2. Tidsrum</div>
                        <div class="shift-wizard-step" data-step-indicator="3">3. Bekræft</div>
                    </div>

                    <form action="{{ route('tasks.shifts.store', $task->id) }}" method="POST" class="task-form" id="shiftWizard" novalidate>
                    @csrf

                    <div class="form-step" data-step="1">
                        <div class="step-header">
                            <div class="step-number">1</div>
                            <div class="step-content">
                                <h3>Vælg person</h3>
                                <p>Tildel vagten til en deltager</p>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="userId" class="form-label">Vælg Bruger</label>
                            <select name="userId" id="userId" class="form-select" required>
                                <option value="">Vælg en bruger</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('userId') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }} ({{ $user->email }})
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-actions form-actions-end">
                            <button type="button" class="btn primary-btn" data-next>Fortsæt</button>
                        </div>
                    </div>

                        <div class="form-step" data-step="2" hidden>
                        <div class="step-header">
                            <div class="step-number">2</div>
                            <div class="step-content">
                                <h3>Tidsrum</h3>
                                <p>Vælg start- og sluttid</p>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group">
                                <label for="startTime" class="form-label">Starttid</label>
                                <input type="datetime-local" name="startTime" id="startTime" class="form-input" 
                                       value="{{ old('startTime') }}" required
                                       @if($task->event && $task->event->startDate) min="{{ \Carbon\Carbon::parse($task->event->startDate)->format('Y-m-d\TH:i') }}" @endif
                                       @if($task->event && $task->event->endDate) max="{{ \Carbon\Carbon::parse($task->event->endDate)->format('Y-m-d\TH:i') }}" @endif>
                            </div>
                            <div class="form-group">
                                <label for="endTime" class="form-label">Sluttid</label>
                                <input type="datetime-local" name="endTime" id="endTime" class="form-input" 
                                       value="{{ old('endTime') }}" required
                                       @if($task->event && $task->event->startDate) min="{{ \Carbon\Carbon::parse($task->event->startDate)->format('Y-m-d\TH:i') }}" @endif
                                       @if($task->event && $task->event->endDate) max="{{ \Carbon\Carbon::parse($task->event->endDate)->format('Y-m-d\TH:i') }}" @endif>
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
                                <p>Tjek oplysningerne og opret vagten</p>
                            </div>
                        </div>
                        <div class="form-group review-card">
                            <div class="review-grid">
                                <div>
                                    <div class="review-label">Person</div>
                                    <div id="reviewUser" class="review-value"></div>
                                </div>
                                <div>
                                    <div class="review-label">Start</div>
                                    <div id="reviewStart" class="review-value"></div>
                                </div>
                                <div>
                                    <div class="review-label">Slut</div>
                                    <div id="reviewEnd" class="review-value"></div>
                                </div>
                            </div>
                        </div>
                        <div class="form-actions form-actions-split">
                            <div class="form-actions-inline">
                                <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn secondary-btn">Annuller</a>
                                <button type="submit" class="btn primary-btn"><i class="fas fa-plus"></i>Opret Vagt</button>
                            </div>
                        </div>
                    </div>
                </form>
                </article>

                <aside class="shift-create-aside">
                    <div class="edit-card">
                        <h3>Tip til god planlægning</h3>
                        <p>Hold vagter korte og konkrete. Del hellere lange vagter i mindre blokke, så de er lettere at overtage.</p>

                        @if($task->event && $task->event->startDate && $task->event->endDate)
                            <div class="task-edit-meta-item">
                                <span>Begivenhedens tidsrum</span>
                                <strong>
                                    {{ \Carbon\Carbon::parse($task->event->startDate)->format('d.m.Y H:i') }} -
                                    {{ \Carbon\Carbon::parse($task->event->endDate)->format('d.m.Y H:i') }}
                                </strong>
                            </div>
                        @endif

                        <div class="task-edit-meta-item">
                            <span>Trin</span>
                            <strong>3 enkle trin til en gyldig vagt</strong>
                        </div>
                    </div>
                </aside>
            </section>
        </div>
    </main>

    @include('partials.footer')

    <script src="https://unpkg.com/auto-animate@1.0.2/dist/auto-animate.js"></script>
    <script>
        // Wizard controls and UX
        (function(){
            const form = document.getElementById('shiftWizard');
            if (!form) return;
            const steps = Array.from(form.querySelectorAll('.form-step'));
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
                const userText = userSel && userSel.options[userSel.selectedIndex] ? userSel.options[userSel.selectedIndex].text : '';
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

            // Auto-fill end time when start changes
            const startEl = form.querySelector('#startTime');
            const endEl = form.querySelector('#endTime');
            if (startEl && endEl) {
                startEl.addEventListener('change', function(){
                    const dt = new Date(this.value);
                    if (dt instanceof Date && !isNaN(dt)) {
                        const plus1h = new Date(dt.getTime() + 60*60*1000);
                        // Format in local time (YYYY-MM-DDTHH:mm) instead of UTC
                        const pad = (n) => String(n).padStart(2, '0');
                        const local = `${plus1h.getFullYear()}-${pad(plus1h.getMonth()+1)}-${pad(plus1h.getDate())}T${pad(plus1h.getHours())}:${pad(plus1h.getMinutes())}`;
                        if (!endEl.value || new Date(endEl.value) <= dt) {
                            endEl.value = local;
                        }
                    }
                });
            }

            // Auto-animate for form elements
            if (typeof autoAnimate !== 'undefined') {
                autoAnimate(document.querySelector('.task-form-wrapper'));
            }

            // Init
            showStep(0);
        })();
    </script>
</body>
</html>
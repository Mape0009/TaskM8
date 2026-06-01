<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="no-js">
<head>
    @php
        $pageTitle = __('ui.shift_edit_page_title', ['task' => $task->taskName]);
        $metaDescription = __('ui.shift_edit_meta', ['task' => $task->taskName]);
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
    <link rel="stylesheet" href="{{ asset('css/shifts-edit.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://unpkg.com/auto-animate@1.0.2/dist/auto-animate.js"></script>
</head>
<body>
    @include('partials.header', ['currentPage' => 'tasks'])

    <main class="main-content-full">
        <div class="edit-container">
            <div class="edit-header">
                <h1 class="edit-title">{{ __('ui.shift_edit_title', ['task' => $task->taskName]) }}</h1>
                <div class="header-actions">
                    <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn secondary-btn">
                        <i class="fas fa-arrow-left"></i>
                        {{ __('ui.back_to_shifts') }}
                    </a>
                </div>
            </div>

            @if($errors->any())
                <div class="alert alert-error">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="task-form-wrapper">
                <form action="{{ route('tasks.shifts.update', [$task->id, $shift->id]) }}" method="POST" class="edit-form task-form" novalidate>
                    @csrf
                    @method('PUT')
                    
                    <div class="form-row">
                        <label for="userId">{{ __('ui.shift_select_person_required') }}</label>
                        <select id="userId" name="userId" required class="form-input">
                            <option value="">{{ __('ui.select_person_placeholder') }}</option>
                            @foreach($users as $user)
                                <option value="{{ $user->id }}" {{ old('userId', $shift->userId) == $user->id ? 'selected' : '' }}>
                                    {{ $user->name ?? $user->email ?? __('ui.unknown_user') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-row">
                        <label for="startTime">{{ __('ui.shift_start_label') }} *</label>
                        <input type="datetime-local" id="startTime" name="startTime" 
                               value="{{ old('startTime', \Carbon\Carbon::parse($shift->startTime)->format('Y-m-d\\TH:i')) }}"
                               required class="form-input"
                               @if($task->event && $task->event->startDate) min="{{ \Carbon\Carbon::parse($task->event->startDate)->format('Y-m-d\\TH:i') }}" @endif
                               @if($task->event && $task->event->endDate) max="{{ \Carbon\Carbon::parse($task->event->endDate)->format('Y-m-d\\TH:i') }}" @endif>
                    </div>

                    <div class="form-row">
                        <label for="endTime">{{ __('ui.shift_end_label') }} *</label>
                        <input type="datetime-local" id="endTime" name="endTime" 
                               value="{{ old('endTime', \Carbon\Carbon::parse($shift->endTime)->format('Y-m-d\\TH:i')) }}"
                               required class="form-input"
                               @if($task->event && $task->event->startDate) min="{{ \Carbon\Carbon::parse($task->event->startDate)->format('Y-m-d\\TH:i') }}" @endif
                               @if($task->event && $task->event->endDate) max="{{ \Carbon\Carbon::parse($task->event->endDate)->format('Y-m-d\\TH:i') }}" @endif>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn primary-btn">
                            <i class="fas fa-check"></i>
                            {{ __('ui.save_changes') }}
                        </button>
                        <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn secondary-btn">
                            <i class="fas fa-times"></i>
                            {{ __('ui.cancel') }}
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </main>

    @include('partials.footer')

    <script>
        (function(){
            const startEl = document.getElementById('startTime');
            const endEl = document.getElementById('endTime');
            if (!startEl || !endEl) return;
            const pad = (n) => String(n).padStart(2, '0');
            startEl.addEventListener('change', function(){
                const dt = new Date(this.value);
                if (dt instanceof Date && !isNaN(dt)) {
                    const plus1 = new Date(dt.getTime() + 60*60*1000);
                    const local = `${plus1.getFullYear()}-${pad(plus1.getMonth()+1)}-${pad(plus1.getDate())}T${pad(plus1.getHours())}:${pad(plus1.getMinutes())}`;
                    if (!endEl.value || new Date(endEl.value) <= dt) endEl.value = local;
                }
            });
        })();

        if (typeof autoAnimate !== 'undefined') {
        autoAnimate(document.querySelector('.task-form-wrapper'));
        }
    </script>
</body>
</html>

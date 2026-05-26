<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    @php
        $pageTitle = isset($event) ? ($event->eventName . ' | TaskM8') : __('ui.task_overview') . ' | TaskM8';
        $metaDescription = isset($event)
            ? __('ui.task_page_event_intro', ['event' => $event->eventName])
            : __('ui.task_page_intro');
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Anke+Devanagari&display=swap" rel="stylesheet">
</head>
<body class="tasks-simple-page">
@include('partials.header', ['currentPage' => 'tasks'])

<main class="main-content-full">
    <div class="overview-shell tasks-simple-shell">
        @php
            $taskCount = $tasks->count();
            $totalShifts = $tasks->sum(function($task) { return $task->shifts->count(); });
            $currentUserRoleForEvent = null;
            $currentUserForEvent = auth()->user();
            if ($currentUserForEvent && isset($event)) {
                $p = \App\Models\EventParticipant::where('eventId', $event->id)
                    ->where('userId', $currentUserForEvent->id)
                    ->first();
                $currentUserRoleForEvent = $p?->eventRole;
            }
        @endphp

        <section class="tasks-page-header">
            <div>
                <h1>{{ isset($event) ? __('ui.task_overview_for', ['event' => $event->eventName]) : __('ui.task_overview') }}</h1>
                <p>{{ isset($event) ? __('ui.task_page_event_intro', ['event' => $event->eventName]) : __('ui.task_page_intro') }}</p>
                <p class="tasks-page-meta">{{ $taskCount }} {{ __('ui.task_overview') }} · {{ $totalShifts }} {{ __('ui.task_shifts') }}</p>
            </div>
            <div class="tasks-page-actions">
                @if(isset($event) && \App\Http\RolePermissions\Permissions::hasPermission($currentUserRoleForEvent ?? 'participant', 'create-task'))
                    <a href="{{ route('events.tasks.create.form', ['eventId' => $event->id]) }}" class="btn primary-btn">{{ __('ui.create_task_btn') }}</a>
                @elseif(!isset($event))
                    <a href="{{ url('/events') }}" class="btn secondary-btn">{{ __('ui.find_event_btn') }}</a>
                @endif
            </div>
        </section>

        <section class="task-listing tasks-simple-listing">
                <div class="task-simple-list" role="table" aria-label="{{ __('ui.task_overview') }}">
                <div class="task-simple-row task-simple-row-head" role="row">
                    <div role="columnheader">{{ __('ui.task_name') }}</div>
                    <div role="columnheader">{{ __('ui.task_shifts') }}</div>
                    <div role="columnheader" class="task-simple-actions">{{ __('ui.task_actions') }}</div>
                </div>

                @forelse($tasks as $task)
                    <div class="task-simple-row" role="row">
                        <div role="cell" class="task-simple-main">
                            <strong>{{ $task->taskName }}</strong>
                            @if($task->description)
                                <span>{{ $task->description }}</span>
                            @endif
                        </div>
                        <div role="cell">{{ trans_choice('ui.shift_count', $task->shifts->count(), ['count' => $task->shifts->count()]) }}</div>
                        <div role="cell" class="task-simple-actions">
                            @php
                                $eventForTask = isset($event) ? $event : ($task->eventId ? \App\Models\Event::find($task->eventId) : null);
                                $currentUserRole = null;
                                $currentUser = auth()->user();
                                if ($currentUser && $eventForTask) {
                                    $p = \App\Models\EventParticipant::where('eventId', $eventForTask->id)
                                        ->where('userId', $currentUser->id)
                                        ->first();
                                    $currentUserRole = $p?->eventRole;
                                }
                            @endphp
                            @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'view-shift'))
                                <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn primary-btn">{{ __('ui.task_shifts') }}</a>
                            @endif
                            @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'edit-task'))
                                <a href="/tasks/{{ $task->id }}/edit" class="btn secondary-btn">{{ __('ui.edit') }}</a>
                            @endif
                            @if(\App\Http\RolePermissions\Permissions::hasPermission($currentUserRole ?? 'participant', 'delete-task'))
                                <button type="button" class="btn danger-btn" aria-label="{{ __('ui.delete_task') }}" onclick="openDeleteModal({{ $task->id }})">{{ __('ui.delete') }}</button>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="task-empty-state">
                        <h3>{{ __('ui.no_tasks_yet') }}</h3>
                        <p>{{ __('ui.start_first_task') }}</p>
                        @if(isset($event) && \App\Http\RolePermissions\Permissions::hasPermission($currentUserRoleForEvent ?? 'participant', 'create-task'))
                            <a href="{{ route('events.tasks.create.form', ['eventId' => $event->id]) }}" class="btn primary-btn">{{ __('ui.create_task_btn') }}</a>
                        @endif
                    </div>
                @endforelse
            </div>
        </section>
    </div>
</main>

@auth
<div id="delete-modal" class="confirm-modal" role="dialog" aria-modal="true" aria-labelledby="confirm-title" style="display:none;">
    <div class="confirm-modal-content">
        <div class="confirm-modal-body">
            <svg fill="currentColor" viewBox="0 0 20 20" class="confirm-icon" xmlns="http://www.w3.org/2000/svg">
                <path clip-rule="evenodd" d="M9 2a1 1 0 00-.894.553L7.382 4H4a1 1 0 000 2v10a2 2 0 002 2h8a2 2 0 002-2V6a1 1 0 100-2h-3.382l-.724-1.447A1 1 0 0011 2H9zM7 8a1 1 0 012 0v6a1 1 0 11-2 0V8zm5-1a1 1 0 00-1 1v6a1 1 0 102 0V8a1 1 0 00-1-1z" fill-rule="evenodd"></path>
            </svg>
            <h2 id="confirm-title" class="confirm-title">{{ __('ui.confirm_are_you_sure') }}</h2>
            <p class="confirm-text">{{ __('ui.confirm_delete_task') }}</p>
        </div>
        <div class="confirm-actions">
            <button type="button" class="confirm-btn cancel" onclick="closeDeleteModal()">{{ __('ui.cancel') }}</button>
            <form id="delete-form" method="POST" style="display:inline;">
                @csrf
                @method('DELETE')
                <button type="submit" class="confirm-btn danger">{{ __('ui.delete') }}</button>
            </form>
        </div>
    </div>
</div>
@endauth

<script src="{{ asset('js/task.js') }}"></script>
@include('partials.footer')
</body>
</html>


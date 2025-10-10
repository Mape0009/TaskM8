<!DOCTYPE html>
<html lang="da">
<head>
    @php
        $pageTitle = isset($event) ? ($event->eventName . ' – Opgaver | TaskM8') : 'Opgaver | TaskM8';
        $metaDescription = isset($event)
            ? ('Se og administrer opgaver for ' . $event->eventName)
            : 'Se og administrer dine opgaver relateret til begivenheder i TaskM8.';
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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
@include('partials.header', ['currentPage' => 'tasks'])

<main class="main-content-full">
    <section class="task-listing">
        <div class="task-listing-header" style="margin-bottom: 1rem;">
            <div class="header-content">
                <h2>{{ isset($event) ? 'Opgaver for ' . $event->eventName : 'Mine opgaver' }}</h2>
                @if(isset($event))
                    <p class="header-description">Administrer opgaver og vagter for denne begivenhed</p>
                @else
                    <p class="header-description">Se og administrer alle dine opgaver</p>
                @endif
            </div>
            <div class="header-actions" style="gap: 0.75rem;">
                @if(isset($event))
                    <a href="{{ route('events.tasks.create.form', ['eventId' => $event->id]) }}" class="btn primary-btn">
                        <i class="fas fa-plus"></i>
                        Opret Opgave
                    </a>
                @else
                    <a href="/tasks/create" class="btn primary-btn">
                        <i class="fas fa-plus"></i>
                        Opret Opgave
                    </a>
                @endif
            </div>
        </div>

        <div class="task-list" style="margin-top: 1rem; gap: 1.25rem;">
            @foreach($tasks as $task)
                <div class="task-card">
                    <div class="task-header">
                        <div class="task-title-section">
                            <h3>{{ $task->taskName }}</h3>
                            @if($task->description)
                                <p class="task-description">{{ $task->description }}</p>
                            @endif
                        </div>
                        <div class="task-stats">
                            <div class="stat-item">
                                <i class="fas fa-users"></i>
                                <span>{{ $task->shifts->count() }} vagt{{ $task->shifts->count() !== 1 ? 'er' : '' }}</span>
                            </div>
                        </div>
                    </div>

                    <div class="task-actions">
                        <a href="{{ route('tasks.shifts.index', $task->id) }}" class="btn primary-btn">
                            <i class="fas fa-list"></i>
                            Vagter
                        </a>
                        <a href="/tasks/{{ $task->id }}/edit" class="btn secondary-btn">
                            <i class="fas fa-edit"></i>
                            Rediger Opgave
                        </a>
                    </div>
                </div>
            @endforeach
        </div>
    </section>
</main>

    @include('partials.footer')
</body>
</html>
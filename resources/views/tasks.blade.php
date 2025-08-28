<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Task overview</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
    <main class="main-content-full">
        <section class="task-listing">
            <h2>My Tasks</h2>
            <div class="task-list">
                @foreach($tasks as $task)
                    <div class="task-card">
                        <div class="task-header">
                            <h3>{{ $task->taskName }}</h3>
                                @if($task->description)
                                    <p>{{ $task->description }}</p>
                                @endif
                        </div>
                        <div class="task-actions">
                            <a href="/tasks/{{ $task->id }}" class="btn primary-btn">View Details</a>
                        </div>
                    </div>
                @endforeach
            </div>
        </section>
</body>
</html>
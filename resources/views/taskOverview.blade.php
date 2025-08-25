<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <main class="main-content-full">
        <header class="content-header">
            <h1>Tasks</h1>
        </header>
        <section class="task-listing">
            <h2>My Tasks</h2>
            <div class="task-list">
                @foreach($tasks as $task)
                    <div class="task-card">
                        <div class="task-header">
                            <h3>{{ $task->taskName }}</h3>
                        </div>
                        <div class="task-actions">
                            <a href="/tasks/{{ $task->id }}" class="btn primary-btn">View Details</a>
                        </div>
                    </div>
                    <p>No tasks found.</p>
                @endforeach
            </div>
        </section>
</body>
</html>
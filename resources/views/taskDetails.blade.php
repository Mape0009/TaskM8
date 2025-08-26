<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Details</title>
</head>
<body>  
    <main class="main-content-full">
        <header class="content-header">
            <h1>Task Details</h1>
        </header>
        <section class="task-details">
            <div class="task-card">
                <div class="task-header">
                    <h3>{{ $task->taskName }}</h3>
                </div>
                <div class="task-actions">
                    <a href="/tasks/{{ $task->id }}/edit" class="btn primary-btn">Edit Task</a>
                    <form action="{{ route('task.delete', ['id' => $task->id]) }}" method="POST">
                        <input type="hidden" name="id" value="{{ $task->id }}">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn danger-btn">Delete Task</button>
                    </form>
                </div>
            </div>
        </section>
</body>
</html>
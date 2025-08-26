<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
</head>
<body>
    <h1>Edit Task</h1>
    <form action="{{ route('task.update', ['id' => $tasks->id]) }}" method="POST">
        @csrf
        @method('PUT')
        <label for="taskName">Task Name:</label>
        <input type="text" name="taskName" value="{{ $tasks->taskName }}" placeholder="Task Name">
        <label for="event_id">Event:</label>
        <select name="event_id" id="event_id" required>
            @foreach($events as $event)
                <option value="{{ $event->id }}">{{ $event->eventName }}</option>
            @endforeach
        </select>
        <button type="submit">Update Task</button>
    </form>
</body>
</html>

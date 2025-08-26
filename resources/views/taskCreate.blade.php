<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="{{ route('task.create') }}" method="POST">
        @csrf
        <label>Name:</label>
        <input type="text" name="taskName" placeholder="task Name">
        <label>Event:</label>
        <select name="event_id" id="event_id" required>
            @foreach($events as $event)
                <option value="{{ $event->id }}">{{ $event->eventName }}</option>
            @endforeach
        </select>
        <button type="submit">Create task</button>
    </form>   
</body>
</html>

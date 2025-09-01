<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Task</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="edit-container">
    <div class="edit-header" style="display: flex; align-items: center; justify-content: space-between;">
        <h1 class="edit-title">Edit Task</h1>
        <a href="{{ url()->previous() }}" class="btn white-btn">← Back</a>
    </div>

    <div class="edit-card">
        <form action="{{ route('task.update', ['id' => $tasks->id]) }}" method="POST" class="edit-form">
            @csrf
            @method('PUT')

            <div class="form-row">
                <label for="taskName">Task Name:</label>
                <input type="text" name="taskName" value="{{ $tasks->taskName }}" placeholder="Task Name">
            </div>

            <div class="form-row">
                <label for="description">Description:</label>
                <textarea name="description" placeholder="Task description">{{ $tasks->description }}</textarea>
            </div>

            <div class="form-row">
                <label for="event_id">Event:</label>
                <select name="event_id" id="event_id" required>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}" {{ $event->id == $tasks->event_id ? 'selected' : '' }}>
                            {{ $event->eventName }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" placeholder="Task location" value="{{ old('location', $tasks->location) }}">
            </div>

            <div class="form-row">
                <label for="startDate">Start Date & Time:</label>
                <input type="datetime-local" id="startDate" name="startDate" required value="{{ old('startDate', \Carbon\Carbon::parse($tasks->start_time)->format('Y-m-d\TH:i')) }}">
            </div>

            <div class="form-row">
                <label for="endDate">End Date & Time:</label>
                <input type="datetime-local" id="endDate" name="endDate" required value="{{ old('endDate', \Carbon\Carbon::parse($tasks->end_time)->format('Y-m-d\TH:i')) }}">
            </div>


            <div class="form-actions">
                <button type="submit" class="btn primary-btn">Update Task</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>

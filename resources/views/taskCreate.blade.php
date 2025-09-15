<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>create task</title>
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    <link rel="stylesheet" href="{{ asset('css/event.css') }}">
    <link rel="stylesheet" href="{{ asset('css/modal.css') }}">
    <link rel="stylesheet" href="{{ asset('css/task.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
</head>
<body>
<div class="edit-container">
    <div class="edit-header" style="display: flex; align-items: center; justify-content: space-between;">
        <h2 class="edit-title">Create New Task</h2>
        <a href="{{ url()->previous() }}" class="btn white-btn">← Back</a>
    </div>

    <div class="task-form-wrapper">
        <form method="GET" action="{{ route('task.create.form') }}" class="form-row search-bar">
            <input type="text" name="q" placeholder="Search users..." value="{{ request('q') }}"/>
            <button type="submit" class="btn primary-btn">Search</button>
        </form>

        <form action="{{ route('task.create') }}" method="POST" class="edit-form">
            @csrf

            <div class="form-row">
                <label for="taskName">Name:</label>
                <input type="text" id="taskName" name="taskName" placeholder="Task Name">
            </div>

            <div class="form-row">
                <label for="description">Description:</label>
                <textarea id="description" name="description" placeholder="Task description"></textarea>
            </div>

            <div class="form-row">
                <label for="event_id">Event:</label>
                <select name="event_id" id="event_id" required>
                    @foreach($events as $event)
                        <option value="{{ $event->id }}">{{ $event->eventName }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-row">
                <label for="location">Location:</label>
                <input type="text" id="location" name="location" placeholder="Task location">
            </div>

            <div class="form-row">
                <label for="startDate">Start Date & Time:</label>
                <input type="datetime-local" id="startDate" name="startDate" required>
            </div>

            <div class="form-row">
                <label for="endDate">End Date & Time:</label>
                <input type="datetime-local" id="endDate" name="endDate" required>
            </div>

            <div class="form-row">
                <label for="user_ids">Assign Users:</label>
                <div style="height: 4rem;"></div>
                    <div class="checkbox-group" style="max-height: 200px; overflow-y: auto; border: 1.5px solid #e5e7eb; border-radius: 8px; padding: 1rem; background: #f9fafb; display: flex; flex-wrap: wrap; gap: 0.75rem;">
                        @forelse ($users as $user)
                            <label style="display: flex; align-items: center; gap: 0.5rem; font-size: 0.875rem; font-weight: 500; color: #374151;">
                            <input type="checkbox" name="user_ids[]" value="{{ $user->id }}"
                               {{ in_array($user->id, old('user_ids', [])) ? 'checked' : '' }}
                               style="width: 16px; height: 16px; accent-color: #667eea; margin: 0;" />
                            <span>{{ $user->name }}</span>
                            </label>
                            @empty
                            <p style="font-size: 0.875rem; color: #6b7280;">No users found.</p>
                        @endforelse
                    </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn primary-btn">Create Task</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>

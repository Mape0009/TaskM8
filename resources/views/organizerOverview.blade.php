<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Email</title>
</head>
<body>
    <h1>This is an overview of participants</h1>
    <form action="{{ route('events.participants', ['eventId' => $eventId]) }}" method="GET">
        @csrf
        @foreach ($participants as $participant)
                <h2>{{$participant->event->eventName}}</h2>
            <div>
                <p>Name: {{ $participant->user->name }} | Email: {{ $participant->user->email }}</p>
                @if ($currentUser && $participant->event->ownerId === $currentUser->id)
                <select name="role">
                    <option value="" disabled selected>{{$participant->role}}</option>
                    <option value="owner" {{ $participant->role === 'owner' ? 'selected' : '' }}>Owner</option>
                    <option value="coOwner" {{ $participant->role === 'coOwner' ? 'selected' : '' }}>Co-Owner</option>
                    <option value="taskManager" {{ $participant->role === 'taskManager' ? 'selected' : '' }}>Task Manager</option>
                    <option value="taskWorker" {{ $participant->role === 'taskWorker' ? 'selected' : '' }}>Task Worker</option>
                    <option value="participant" {{ $participant->role === 'participant' ? 'selected' : '' }}>Participant</option>
                </select>
                @endif
                <br>
            </div>
        @endforeach
    </form>
</body>
</html>
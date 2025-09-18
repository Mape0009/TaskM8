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

        @php
            $isOwner = false;
            foreach ($participants as $p) {
                if ($p->userId === $currentUser->id && $p->eventRole === $eventRole::owner->name) {
                    $isOwner = true;
                    break;
                }
            }
        @endphp

        @foreach ($participants as $participant)
            <h2>{{$participant->event->eventName}}</h2>
            <div>
                <p>Name: {{ $participant->user->name }} | Email: {{ $participant->user->email }}</p>
                <form action="{{ route('events.roleUpdate', ['participantId' => $participant->id]) }}" method="POST">
                    @csrf
                    @if ($isOwner)
                        <select name="eventRole">
                            <option value="" disabled selected>{{$participant->eventRole}}</option>
                            <option value="owner" {{ $participant->eventRole === $eventRole::owner->name ? 'selected' : '' }}>Owner</option>
                            <option value="coOwner" {{ $participant->eventRole === $eventRole::coOwner->name ? 'selected' : '' }}>Co-Owner</option>
                            <option value="taskManager" {{ $participant->eventRole === $eventRole::taskManager->name ? 'selected' : '' }}>Task Manager</option>
                            <option value="taskWorker" {{ $participant->eventRole === $eventRole::taskWorker->name ? 'selected' : '' }}>Task Worker</option>
                            <option value="participant" {{ $participant->eventRole === $eventRole::participant->name ? 'selected' : '' }}>Participant</option>
                        </select>
                    @endif
                    <br>
                </form>
            </div>
        @endforeach
    </form>
</body>
</html>
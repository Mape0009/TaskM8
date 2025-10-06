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
                </form>
                @php
                    // Determine current user's role for this event
                    $currentParticipant = $participants->firstWhere('userId', $currentUser->id);
                    $currentRole = $currentParticipant?->eventRole ?? 'participant';
                @endphp
                @php
                    // Do not show delete button next to owners
                    $isTargetOwner = ($participant->eventRole === $eventRole::owner->name);
                    // If current user is coOwner, they should not be able to delete another coOwner
                    $isCurrentCoOwner = ($currentRole === $eventRole::coOwner->name);
                    $isTargetCoOwner = ($participant->eventRole === $eventRole::coOwner->name);
                @endphp

                @if (! $isTargetOwner && \App\Http\RolePermissions\Permissions::hasPermission($currentRole, 'delete-participant') && !($isCurrentCoOwner && $isTargetCoOwner))
                    <form action="{{ route('events.deleteParticipant', ['id' => $participant->id]) }}" method="POST" style="display:inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit">Delete</button>
                    </form>
                @endif
                <br>
            </div>
        @endforeach
    </form>
</body>
</html>
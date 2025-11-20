<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Test Email</title>
</head>
<body>
    <h1>This is an overview of participants</h1>

    @php
        $isOwner = false;
        foreach ($participants as $p) {
            if ($p->userId === $currentUser->id && $p->eventRole === $eventRole::owner->name || $p->userId === $currentUser->id && $p->eventRole === $eventRole::coOwner->name || $p->userId === $currentUser->id && $p->eventRole === $eventRole::taskManager->name) {
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
                        @php
                            // Determine current user's role for this event
                            $currentParticipant = $participants->firstWhere('userId', $currentUser->id);
                            $currentRole = $currentParticipant?->eventRole ?? 'participant';

                            // Helper to check if current user can assign a given role
                            $canAssignRole = function($currentRole, $targetRole) use ($eventRole) {
                                $map = [
                                    'coOwner' => 'manage-coOwners',
                                    'taskManager' => 'manage-taskManagers',
                                    'taskWorker' => 'manage-taskWorkers',
                                    'participant' => 'manage-participants',
                                ];
                                if (!isset($map[$targetRole])) return false;
                                return \App\Http\RolePermissions\Permissions::hasPermission($currentRole, $map[$targetRole]);
                            };
                        @endphp

                        <select name="eventRole">
                            {{-- Always include the current role as a selectable option so a value is submitted if unchanged --}}
                            <option value="{{ $participant->eventRole }}" selected>{{ $participant->eventRole }}</option>
                            {{-- Owner cannot be assigned via this form --}}
                            @if ($participant->eventRole === $eventRole::owner->name)
                                <option value="owner" selected>Owner</option>
                            @endif

                            @if ($canAssignRole($currentRole, 'coOwner'))
                                <option value="coOwner" {{ $participant->eventRole === $eventRole::coOwner->name ? 'selected' : '' }}>Co-Owner</option>
                            @endif
                            @if ($canAssignRole($currentRole, 'taskManager'))
                                <option value="taskManager" {{ $participant->eventRole === $eventRole::taskManager->name ? 'selected' : '' }}>Task Manager</option>
                            @endif
                            @if ($canAssignRole($currentRole, 'taskWorker'))
                                <option value="taskWorker" {{ $participant->eventRole === $eventRole::taskWorker->name ? 'selected' : '' }}>Task Worker</option>
                            @endif
                            @if ($canAssignRole($currentRole, 'participant'))
                                <option value="participant" {{ $participant->eventRole === $eventRole::participant->name ? 'selected' : '' }}>Participant</option>
                            @endif
                        </select>
                        <button type="submit">Update role</button>
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
</body>
</html>
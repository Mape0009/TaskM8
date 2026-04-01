<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Http\RolePermissions\Permissions;
use Illuminate\Support\Collection;

class ShiftController extends Controller
{
    public function index($taskId)
    {
        // Load task first so we can check event-level permissions
        $task = Task::with(['shifts.user'])->findOrFail($taskId);

        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $task->eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($task->eventId);
        if (!Permissions::hasPermission($role, 'view-shift')) {
            abort(403, 'Ikke tilladt.');
        }

        return view('shifts.index', compact('task'));
    }

    public function create($taskId)
    {
        $task = Task::findOrFail($taskId);
        $users = $this->eligibleAssigneesForEvent($task->eventId);

        return view('shifts.create', compact('task', 'users'));
    }

    public function store(Request $request, $taskId)
    {
        $user = auth()->user();
        $task = Task::findOrFail($taskId);
        $currentParticipant = EventParticipant::where('eventId', $task->eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($task->eventId);
        if (!Permissions::hasPermission($role, 'create-shift')) {
            abort(403, 'Ikke tilladt.');
        }
        
        $request->validate([
            'userId' => 'required|exists:users,id',
            'startTime' => 'required|date',
            'endTime' => 'required|date|after:startTime',
        ], [
            'userId.required' => 'Vælg en bruger.',
            'userId.exists' => 'Den valgte bruger findes ikke.',
            'startTime.required' => 'Starttidspunkt skal angives.',
            'startTime.date' => 'Starttidspunkt skal være en gyldig dato.',
            'endTime.required' => 'Sluttidspunkt skal angives.',
            'endTime.date' => 'Sluttidspunkt skal være en gyldig dato.',
            'endTime.after' => 'Sluttidspunkt skal være efter starttidspunkt.',
        ]);

        $eligibleUserIds = $this->eligibleAssigneesForEvent($task->eventId)->pluck('id');
        if (!$eligibleUserIds->contains((int) $request->userId)) {
            return redirect()->back()->withErrors(['userId' => 'Brugeren er ikke tilmeldt/organisator for denne begivenhed.'])->withInput();
        }
        
        $hasOverlap = Shift::where('taskId', $taskId)
                            ->where('userId', $request->userId)
                            ->where(function ($q) use ($request) {
                                $q->where('startTime', '<', $request->endTime)
                                  ->where('endTime', '>', $request->startTime);
                            })
                            ->exists();

        if ($hasOverlap) {
            return back()->withErrors(['startTime' => 'Denne vagt overlapper med en eksisterende vagt for brugeren.', 'endTime' => '']);
        }

        try {
            Shift::create([
                'taskId' => $taskId,
                'userId' => $request->userId,
                'startTime' => $request->startTime,
                'endTime' => $request->endTime,
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return redirect()->back()->withErrors(['userId' => 'Kan ikke oprette vagt pga. unik begrænsning. Kør database-migrationerne og prøv igen.'])->withInput();
        }

        return redirect()->route('tasks.shifts.index', $taskId);
    }

    public function edit($taskId, $shiftId)
    {
        $task = Task::findOrFail($taskId);
        $shift = Shift::with('user')->findOrFail($shiftId);
        $users = $this->eligibleAssigneesForEvent($task->eventId);
        
        return view('shifts.edit', compact('task', 'shift', 'users'));
    }

    public function update(Request $request, $taskId, $shiftId)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'startTime' => 'required|date',
            'endTime' => 'required|date|after:startTime',
        ], [
            'userId.required' => 'Vælg en bruger.',
            'userId.exists' => 'Den valgte bruger findes ikke.',
            'startTime.required' => 'Starttidspunkt skal angives.',
            'startTime.date' => 'Starttidspunkt skal være en gyldig dato.',
            'endTime.required' => 'Sluttidspunkt skal angives.',
            'endTime.date' => 'Sluttidspunkt skal være en gyldig dato.',
            'endTime.after' => 'Sluttidspunkt skal være efter starttidspunkt.',
        ]);

        $shift = Shift::findOrFail($shiftId);
        $task = Task::findOrFail($taskId);

        // Ensure selected user is eligible for assignment in this event
        $eligibleUserIds = $this->eligibleAssigneesForEvent($task->eventId)->pluck('id');
        if (!$eligibleUserIds->contains((int) $request->userId)) {
            return redirect()->back()->withErrors(['userId' => 'Brugeren er ikke tilmeldt/organisator for denne begivenhed.'])->withInput();
        }
        
        // Prevent overlapping shifts for the same user and task (excluding current shift)
        $hasOverlap = Shift::where('taskId', $taskId)
                            ->where('userId', $request->userId)
                            ->where('id', '!=', $shiftId)
                            ->where(function ($q) use ($request) {
                                $q->where('startTime', '<', $request->endTime)
                                  ->where('endTime', '>', $request->startTime);
                            })
                            ->exists();

        if ($hasOverlap) {
            return redirect()->back()->withErrors(['startTime' => 'Denne vagt overlapper med en eksisterende vagt for brugeren.', 'endTime' => ''])->withInput();
        }

        $shift->update([
            'userId' => $request->userId,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
        ]);

        return redirect()->route('tasks.shifts.index', $taskId);
    }

    public function destroy($taskId, $shiftId)
    {
        $user = auth()->user();
        $task = Task::findOrFail($taskId);
        $currentParticipant = EventParticipant::where('eventId', $task->eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($task->eventId);
        if (!Permissions::hasPermission($role, 'delete-shift')) {
            abort(403, 'Ikke tilladt.');
        }
        $shift = Shift::findOrFail($shiftId);
        $shift->delete();

        return redirect()->route('tasks.shifts.index', $taskId);
    }

    public function join(Request $request, $taskId)
    {
        $request->validate([]);
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        Shift::firstOrCreate(
            ['taskId' => $taskId, 'userId' => $userId]
        );
        return redirect()->back()->with('success', 'you are participating in the task.');
    }

    public function decline(Request $request, $taskId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        
        $task = Task::findOrFail($taskId);
        
        // Prevent overlap with existing shifts when auto-joining a default 1 time unit
        $proposedStart = now();
        $proposedEnd = now()->addHours(1);

        $hasOverlap = Shift::where('taskId', $taskId)
                            ->where('userId', $userId)
                            ->where(function ($q) use ($proposedStart, $proposedEnd) {
                                $q->where('startTime', '<', $proposedEnd)
                                  ->where('endTime', '>', $proposedStart);
                            })
                            ->exists();

        if ($hasOverlap) {
            return redirect()->back();
        }

        try {
            Shift::create([
                'taskId' => $taskId,
                'userId' => $userId,
                'startTime' => $proposedStart,
                'endTime' => $proposedEnd, // Default 1 hour shift
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return redirect()->back();
        }

        return redirect()->back();
    }

    public function leave($taskId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        
        Shift::where('taskId', $taskId)
             ->where('userId', $userId)
             ->delete();

        return redirect()->back();
    }

    private function eligibleAssigneesForEvent($eventId): Collection
    {
        if (!$eventId) {
            return collect();
        }

        $rolesWithReceiveTask = collect(['owner', 'coOwner', 'taskManager', 'taskWorker'])
            ->filter(fn (string $role) => Permissions::hasPermission($role, 'receiveTask'))
            ->values();

        if ($rolesWithReceiveTask->isEmpty()) {
            return collect();
        }

        $eligibleUserIds = EventParticipant::where('eventId', $eventId)
            ->where('status', 'accepted')
            ->whereIn('eventRole', $rolesWithReceiveTask)
            ->pluck('userId')
            ->unique();

        if ($eligibleUserIds->isEmpty()) {
            return collect();
        }

        return User::whereIn('id', $eligibleUserIds)->get();
    }
}
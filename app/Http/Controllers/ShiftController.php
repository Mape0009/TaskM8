<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use App\Models\Task;
use App\Models\User;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

class ShiftController extends Controller
{
    public function index($taskId)
    {
        $task = Task::with(['shifts.user'])->findOrFail($taskId);
        return view('shifts.index', compact('task'));
    }

    public function create($taskId)
    {
        $task = Task::findOrFail($taskId);
        // Only allow assigning shifts to participants who have accepted
        $participantUserIds = EventParticipant::where('eventId', $task->eventId)
            ->where('status', 'accepted')
            ->pluck('userId');
        $users = User::whereIn('id', $participantUserIds)->get();
        return view('shifts.create', compact('task', 'users'));
    }

    public function store(Request $request, $taskId)
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

        $task = Task::findOrFail($taskId);

        // Ensure selected user is an accepted participant of the event for this task
        $isParticipant = EventParticipant::where('eventId', $task->eventId)
                                         ->where('userId', $request->userId)
                                         ->where('status', 'accepted')
                                         ->exists();
        if (!$isParticipant) {
            return back()->withErrors(['userId' => 'Brugeren er ikke tilmeldt begivenheden for denne opgave.'])->withInput();
        }
        
        // Prevent overlapping shifts for the same user and task
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
            return back()->withErrors(['userId' => 'Kan ikke oprette vagt pga. unik begrænsning. Kør database-migrationerne og prøv igen.'])->withInput();
        }

        // Return without success popup to keep UI clean
        return redirect()->route('tasks.shifts.index', $taskId);
    }

    public function edit($taskId, $shiftId)
    {
        $task = Task::findOrFail($taskId);
        $shift = Shift::with('user')->findOrFail($shiftId);
        $participantUserIds = EventParticipant::where('eventId', $task->eventId)
            ->where('status', 'accepted')
            ->pluck('userId');
        $users = User::whereIn('id', $participantUserIds)->get();
        
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

        // Ensure selected user is an accepted participant of the event for this task
        $isParticipant = EventParticipant::where('eventId', $task->eventId)
                                         ->where('userId', $request->userId)
                                         ->where('status', 'accepted')
                                         ->exists();
        if (!$isParticipant) {
            return back()->withErrors(['userId' => 'Brugeren er ikke tilmeldt begivenheden for denne opgave.'])->withInput();
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
            return back()->withErrors(['startTime' => 'Denne vagt overlapper med en eksisterende vagt for brugeren.', 'endTime' => '']);
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
        $shift = Shift::findOrFail($shiftId);
        $shift->delete();

        return redirect()->route('tasks.shifts.index', $taskId);
    }

    public function join($taskId)
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
}
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
use App\Http\Controllers\Notifications\NotificationMessages;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;

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
            abort(403, __('ui.not_allowed'));
        }

        return view('shifts.index', compact('task'));
    }

    public function create($taskId)
    {
        $task = Task::findOrFail($taskId);
        // Allow assigning shifts to any participants of the event (regardless of status)
        if ($task->eventId) {
            $participantUserIds = EventParticipant::where('eventId', $task->eventId)
                ->pluck('userId');
            $users = $participantUserIds->isEmpty()
                ? User::all()
                : User::whereIn('id', $participantUserIds)->get();
        } else {
            // Fallback for tasks without event linkage
            $users = User::all();
        }
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
            abort(403, __('ui.not_allowed'));
        }
        
        $request->validate([
            'userId' => 'nullable|exists:users,id',
            'startTime' => 'required|date',
            'endTime' => 'required|date|after:startTime',
        ], [
            'userId.exists' => __('ui.shift_validation_user_exists'),
            'startTime.required' => __('ui.shift_validation_start_required'),
            'startTime.date' => __('ui.shift_validation_start_date'),
            'endTime.required' => __('ui.shift_validation_end_required'),
            'endTime.date' => __('ui.shift_validation_end_date'),
            'endTime.after' => __('ui.shift_validation_end_after'),
        ]);

        if ($request->filled('userId')) {
            $participant = EventParticipant::where('eventId', $task->eventId)
                                           ->where('userId', $request->userId)
                                           ->first();
            $roleOk = in_array($participant?->eventRole, ['owner','coOwner','taskManager','taskWorker'], true);
            $isParticipant = ($participant && ($participant->status === 'accepted' || $roleOk))
                             || ($event && (int)$event->ownerId === (int)$request->userId);
            if (!$isParticipant) {
                return redirect()->back()->withErrors(['userId' => __('ui.shift_user_not_participant')])->withInput();
            }

            $hasOverlap = Shift::where('taskId', $taskId)
                                ->where('userId', $request->userId)
                                ->where(function ($q) use ($request) {
                                    $q->where('startTime', '<', $request->endTime)
                                      ->where('endTime', '>', $request->startTime);
                                })
                                ->exists();

            if ($hasOverlap) {
                return back()->withErrors(['startTime' => __('ui.shift_overlap_error'), 'endTime' => '']);
            }
        }

        try {
            Shift::create([
                'taskId' => $taskId,
                'userId' => $request->userId,
                'startTime' => $request->startTime,
                'endTime' => $request->endTime,
                'status' => $request->filled('userId') ? 'accepted' : 'pending',
            ]);
        } catch (\Illuminate\Database\UniqueConstraintViolationException $e) {
            return redirect()->back()->withErrors(['userId' => __('ui.shift_create_unique_error')])->withInput();
        }

        return redirect()->route('tasks.shifts.index', $taskId);
    }

    public function edit($taskId, $shiftId)
    {
        $task = Task::findOrFail($taskId);
        $shift = Shift::with('user')->findOrFail($shiftId);
        if ($task->eventId) {
            $participantUserIds = EventParticipant::where('eventId', $task->eventId)
                ->pluck('userId');
            $users = $participantUserIds->isEmpty()
                ? User::all()
                : User::whereIn('id', $participantUserIds)->get();
        } else {
            $users = User::all();
        }
        
        return view('shifts.edit', compact('task', 'shift', 'users'));
    }

    public function update(Request $request, $taskId, $shiftId)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'startTime' => 'required|date',
            'endTime' => 'required|date|after:startTime',
        ], [
            'userId.required' => __('ui.shift_validation_user_required'),
            'userId.exists' => __('ui.shift_validation_user_exists'),
            'startTime.required' => __('ui.shift_validation_start_required'),
            'startTime.date' => __('ui.shift_validation_start_date'),
            'endTime.required' => __('ui.shift_validation_end_required'),
            'endTime.date' => __('ui.shift_validation_end_date'),
            'endTime.after' => __('ui.shift_validation_end_after'),
        ]);

        $shift = Shift::findOrFail($shiftId);
        $task = Task::findOrFail($taskId);

        // Ensure selected user is eligible for assignment in this event
        $participant = EventParticipant::where('eventId', $task->eventId)
                                       ->where('userId', $request->userId)
                                       ->first();
        $event = Event::find($task->eventId);
        $roleOk = in_array($participant?->eventRole, ['owner','coOwner','taskManager','taskWorker'], true);
        $isParticipant = ($participant && ($participant->status === 'accepted' || $roleOk))
                         || ($event && (int)$event->ownerId === (int)$request->userId);
        if (!$isParticipant) {
            return redirect()->back()->withErrors(['userId' => __('ui.shift_user_not_participant')])->withInput();
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
            return redirect()->back()->withErrors(['startTime' => __('ui.shift_overlap_error'), 'endTime' => ''])->withInput();
        }

        $shift->update([
            'userId' => $request->userId,
            'startTime' => $request->startTime,
            'endTime' => $request->endTime,
            'status' => 'accepted',
        ]);

        // Notify assigned user if shift is updated
        $notificationController = new NotificationController();
        $notificationController->sendNotification(
            $request->userId,
            $task->eventId,
            NotificationMessages::SHIFT_UPDATED
        );

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
            abort(403, __('ui.not_allowed'));
        }
        $shift = Shift::findOrFail($shiftId);
        $shift->delete();

        // Notify assigned user if shift is deleted
        if ($shift->userId) {
            $notificationController = new NotificationController();
            $notificationController->sendNotification(
                $shift->userId,
                $event->id,
                NotificationMessages::SHIFT_DELETED
            );
        }

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

    public function volunteer($taskId, $shiftId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }

        $task = Task::findOrFail($taskId);
        $currentParticipant = EventParticipant::where('eventId', $task->eventId)
            ->where('userId', $userId)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'volunteer-shift')) {
            abort(403, __('ui.not_allowed'));
        }
        
        $shift = Shift::where('taskId', $taskId)
                  ->where('id', $shiftId)
                  ->where('status', 'pending')
                  ->whereNull('userId')
                  ->first();

        if (!$shift) {
            return redirect()->back()->withErrors(['message' => __('ui.shift_no_open_slots')]);
        }
        
        $shift->userId = $userId;
        $shift->status = 'pending';
        $shift->save();

        return redirect()->back()->with('success', __('ui.shift_volunteer_signed_up'));
    }

    public function acceptVolunteer($taskId, $shiftId)
    {
        $user = auth()->user();
        $task = Task::findOrFail($taskId);
        $currentParticipant = EventParticipant::where('eventId', $task->eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'edit-shift')) {
            abort(403, __('ui.not_allowed'));
        }

        $shift = Shift::where('taskId', $taskId)->findOrFail($shiftId);
        if (!$shift->userId) {
            return redirect()->back()->withErrors(['message' => __('ui.shift_no_volunteer_to_approve')]);
        }

        $isVolunteerRequest = $shift->status === 'pending' && $shift->created_at && $shift->updated_at && !$shift->created_at->equalTo($shift->updated_at);
        if (!$isVolunteerRequest) {
            return redirect()->back()->withErrors(['message' => __('ui.shift_only_pending_approve')]);
        }

        $shift->status = 'accepted';
        $shift->save();

        return redirect()->back()->with('success', __('ui.shift_volunteer_approved'));
    }

    public function denyVolunteer($taskId, $shiftId)
    {
        $user = auth()->user();
        $task = Task::findOrFail($taskId);
        $currentParticipant = EventParticipant::where('eventId', $task->eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'edit-shift')) {
            abort(403, __('ui.not_allowed'));
        }

        $shift = Shift::where('taskId', $taskId)->findOrFail($shiftId);
        if (!$shift->userId) {
            return redirect()->back()->withErrors(['message' => __('ui.shift_no_volunteer_to_reject')]);
        }

        $isVolunteerRequest = $shift->status === 'pending' && $shift->created_at && $shift->updated_at && !$shift->created_at->equalTo($shift->updated_at);
        if (!$isVolunteerRequest) {
            return redirect()->back()->withErrors(['message' => __('ui.shift_only_pending_reject')]);
        }

        $shift->userId = null;
        $shift->status = 'pending';
        $shift->save();

        return redirect()->back()->with('success', __('ui.shift_volunteer_rejected'));
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use App\Models\User;
use App\Models\EventParticipant;
use App\Http\RolePermissions\Permissions;
use App\Http\Controllers\Notifications\NotificationMessages;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;


use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function showCreateForm(Request $request)
    {
        $search = $request->query('q');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();

        return view('tasks.create', compact('users'));
    }

    public function index()
    {
        $tasks = Task::all();
        return view('tasks.index', compact('tasks'));
    }

    public function create(Request $request)
    {
        $tasks = new Task();
        $tasks->taskName = $request->input('taskName');
        $tasks->description = $request->input('description');
        $tasks->save();

        $tasks->users()->sync($request->input('user_ids'));

        return redirect('/tasks')->with('success', '');
    }

    public function indexByEvent($eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'view-task')) {
            abort(403, 'Ikke tilladt.');
        }
        $tasks = Task::where('eventId', $eventId)->get();
        return view('tasks.index', compact('tasks', 'event'));
    }

    public function showCreateFormForEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'create-task')) {
            abort(403, 'Ikke tilladt.');
        }
        $search = $request->query('q');
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();
        return view('tasks.create', compact('users', 'event'));
    }

    public function createForEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'create-task')) {
            abort(403, 'Ikke tilladt.');
        }
        $validated = $request->validate([
            'taskName' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = new Task();
        $task->taskName = $validated['taskName'];
        $task->eventId = $event->id;
        $task->description = $validated['description'] ?? '';
        // Default task window inherits event window
        $task->start_time = $event->startDate;
        $task->end_time = $event->endDate;
        $task->save();
        return redirect()->route('events.tasks.index', ['eventId' => $event->id]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $eventId = $task->eventId;
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'edit-task')) {
            abort(403, 'Ikke tilladt.');
        }
        $validated = $request->validate([
            'taskName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'startDate' => 'nullable|date',
            'endDate' => 'nullable|date|after:startDate',
        ]);


        $task->taskName = $validated['taskName'];
        $task->description = $validated['description'] ?? null;
        if (!empty($validated['startDate'])) {
            $task->start_time = $validated['startDate'];
        }
        if (!empty($validated['endDate'])) {
            $task->end_time = $validated['endDate'];
        }
        $task->save();

        // Notify assigned users if task is updated
        $assignedUsers = $task->users;
        foreach ($assignedUsers as $assignedUser) {
            $notificationController = new NotificationController();
            $notificationController->sendNotification(
                $assignedUser->id,
                $eventId,
                NotificationMessages::TASK_UPDATED
            );
        }

        return redirect()->route('events.tasks.index', ['eventId' => $eventId]);
    }

    public function edit($id)
    {
        $tasks = Task::findOrFail($id);
        $eventId = $tasks->eventId;
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'edit-task')) {
            abort(403, 'Ikke tilladt.');
        }
        $users = User::all();
        $event = Event::find($tasks->eventId);
        return view('tasks.edit', compact('tasks', 'users', 'event'));
    }

    public function delete($id)
    {
        $task = Task::findOrFail($id);
        $eventId = $task->eventId;
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'delete-task')) {
            abort(403, 'Ikke tilladt.');
        }

        $assignedUsers = $task->users;
        $task->delete();

        foreach ($assignedUsers as $assignedUser) {
            $notificationController = new NotificationController();
            $notificationController->sendNotification(
                $assignedUser->id,
                $eventId,
                NotificationMessages::TASK_DELETED
            );
        }

        if ($eventId) {
            return redirect()->route('events.tasks.index', ['eventId' => $eventId]);
        }

        return redirect('/tasks');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        // Gate viewing task by event-scoped permission
        $eventId = $task->eventId;
        if ($eventId) {
            $user = auth()->user();
            $currentParticipant = EventParticipant::where('eventId', $eventId)
                ->where('userId', $user?->id)
                ->first();
            $role = $currentParticipant?->eventRole ?? 'participant';
            if (!Permissions::hasPermission($role, 'view-task')) {
                abort(403, 'Ikke tilladt.');
            }
        }
        return view('tasks.details', compact('task'));
    }
}
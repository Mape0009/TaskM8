<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use App\Models\User;
use App\Models\EventParticipant;
use App\Http\RolePermissions\Permissions;


use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function showCreateForm(Request $request)
    {
        $search = $request->query('q');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();

        $events = Event::all();

        return view('taskCreate', compact('events', 'users'));
    }

    public function index($id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'view-task')) {
            abort(403, 'Ikke tilladt.');
        }
        $tasks = Task::all();

        return view('tasks', compact('tasks'));
    }

    public function create(Request $request, $id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'create-task')) {
            abort(403, 'Ikke tilladt.');
        }

        $tasks = new Task();
        $tasks->taskName = $request->input('taskName');
        $tasks->eventId = $request->input('event_id');
        $tasks->description = $request->input('description');
        $tasks->save();

        $tasks->users()->sync($request->input('user_ids'));

        return redirect('/tasks')->with('success', 'Task created successfully!');
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'edit-task')) {
            abort(403, 'Ikke tilladt.');
        }

        $tasks = Task::findOrFail($id);
        $tasks->taskName = $request->input('taskName');
        $tasks->description = $request->input('description');
        $tasks->save();

        return redirect('tasks/' . $id)->with('success', 'Task updated successfully!');
    }

    public function edit($id)
    {
        $tasks = Task::findOrFail($id);
        $events = Event::all();
        $users = User::all();
        
        return view('taskedit', compact('tasks', 'events', 'users'));
    }

    public function delete($id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'delete-task')) {
            abort(403, 'Ikke tilladt.');
        }
        $tasks = Task::findOrFail($id);
        $tasks->delete();

        return redirect('/tasks')->with('success', 'Task deleted successfully!');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('taskDetails', compact('task'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use App\Models\User;


use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function showCreateForm(Request $request)
    {
        $search = $request->query('q');

        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();

        return view('taskCreate', compact('users'));
    }

    public function index()
    {
        $tasks = Task::all();

        return view('tasks', compact('tasks'));
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
        $tasks = Task::where('eventId', $eventId)->get();
        $event = Event::findOrFail($eventId);
        return view('tasks', compact('tasks', 'event'));
    }

    public function showCreateFormForEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $search = $request->query('q');
        $users = User::when($search, function ($query, $search) {
            return $query->where('name', 'like', '%' . $search . '%');
        })->get();
        return view('taskCreate', compact('users', 'event'));
    }

    public function createForEvent(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);
        $validated = $request->validate([
            'taskName' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task = new Task();
        $task->taskName = $validated['taskName'];
        $task->eventId = $event->id;
        $task->description = $validated['description'] ?? '';
        $task->save();
        return redirect()->route('events.tasks.index', ['eventId' => $event->id]);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);
        $validated = $request->validate([
            'taskName' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $task->taskName = $validated['taskName'];
        $task->description = $validated['description'] ?? null;
        $task->save();

        return redirect()->route('events.tasks.index', ['eventId' => $task->eventId]);
    }

    public function edit($id)
    {
        $tasks = Task::findOrFail($id);
        $users = User::all();
        $event = Event::find($tasks->eventId);
        return view('taskedit', compact('tasks', 'users', 'event'));
    }

    public function delete($id)
    {
        $tasks = Task::findOrFail($id);
        $tasks->delete();
        return redirect('/tasks');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('taskDetails', compact('task'));
    }
}

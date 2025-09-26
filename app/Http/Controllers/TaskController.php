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

        return redirect('/tasks')->with('success', 'Task created successfully!');
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
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'user_ids' => 'array',
            'user_ids.*' => 'integer',
        ]);

        // Enforce within event window
        $eventStart = $event->startDate ? \Carbon\Carbon::parse($event->startDate) : null;
        $eventEnd = $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
        $taskStart = \Carbon\Carbon::parse($validated['startDate']);
        $taskEnd = \Carbon\Carbon::parse($validated['endDate']);
        if (($eventStart && $taskStart->lt($eventStart)) || ($eventEnd && $taskEnd->gt($eventEnd))) {
            return back()->withErrors(['startDate' => 'Tiden skal ligge inden for begivenhedens start/slut.'])->withInput();
        }

        $tasks = new Task();
        $tasks->taskName = $validated['taskName'];
        $tasks->eventId = $event->id;
        $tasks->description = $validated['description'] ?? null;
        $tasks->start_time = $taskStart;
        $tasks->end_time = $taskEnd;
        $tasks->save();
        $tasks->users()->sync($request->input('user_ids'));
        return redirect()->route('events.tasks.index', ['eventId' => $event->id]);
    }

    public function update(Request $request, $id)
    {
        $tasks = Task::findOrFail($id);
        $validated = $request->validate([
            'taskName' => 'required|string|max:255',
            'description' => 'nullable|string',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
        ]);

        // Enforce within event window if available
        $event = Event::find($tasks->eventId);
        if ($event) {
            $eventStart = $event->startDate ? \Carbon\Carbon::parse($event->startDate) : null;
            $eventEnd = $event->endDate ? \Carbon\Carbon::parse($event->endDate) : null;
            $taskStart = \Carbon\Carbon::parse($validated['startDate']);
            $taskEnd = \Carbon\Carbon::parse($validated['endDate']);
            if (($eventStart && $taskStart->lt($eventStart)) || ($eventEnd && $taskEnd->gt($eventEnd))) {
                return back()->withErrors(['startDate' => 'Tiden skal ligge inden for begivenhedens start/slut.'])->withInput();
            }
            $tasks->start_time = $taskStart;
            $tasks->end_time = $taskEnd;
        }

        $tasks->taskName = $validated['taskName'];
        $tasks->description = $validated['description'] ?? null;
        $tasks->save();

        return redirect('tasks/' . $id);
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

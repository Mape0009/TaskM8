<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function showCreateForm()
    {
        $events = Event::all();
        return view('taskCreate', compact('events'));
    }

    public function index()
    {
        $tasks = Task::all();

        return view('taskOverview', compact('tasks'));
    }

    public function create(Request $request)
    {
        $tasks = new Task();
        $tasks->taskName = $request->input('taskName');
        $tasks->eventId = $request->input('event_id');
        $tasks->save();


        return redirect('/taskOverview')->with('success', 'Task created successfully!');
    }

    public function update(Request $request, $id)
    {
        $tasks = Task::findOrFail($id);
        $tasks->taskName = $request->input('taskName');
        $tasks->save();

        return response()->json($tasks);
    }

    public function edit($id)
    {
        $tasks = Task::findOrFail($id);
        $events = Event::all();
        return view('taskedit', compact('tasks', 'events'));
    }

    public function delete($id)
    {
        $tasks = Task::findOrFail($id);
        $tasks->delete();

        return redirect('/taskOverview')->with('success', 'Task deleted successfully!');
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return view('taskDetails', compact('task'));
    }
}

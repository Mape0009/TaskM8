<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\Event;
use App\Models\User;


use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function showCreateForm()
    {
        $events = Event::all();
        $users = User::all();

        return view('taskCreate', compact('events', 'users'));
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
        $tasks->eventId = $request->input('event_id');
        $tasks->location = $request->input('location');
        $tasks->description = $request->input('description');
        $tasks->startDate = $request->input('startDate');
        $tasks->endDate = $request->input('endDate');
        $tasks->userId = $request->input('user_id');

        $tasks->save();


        return redirect('/tasks')->with('success', 'Task created successfully!');
    }

    public function update(Request $request, $id)
    {
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

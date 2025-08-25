<?php

namespace App\Http\Controllers;

use App\Models\Task;

use Illuminate\Http\Request;

class TaskController extends Controller
{
    public function index()
    {
        $tasks = Task::all();

        return view('taskOverview', compact('tasks'));
    }

    public function create(Request $request)
    {
        $tasks = new Task();
        $tasks->taskName = $request->input('taskName');
        $tasks->save();

        return response()->json(['message' => 'Task created successfully', 'task' => $tasks]);
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

        return view('edit.task', compact('tasks'));
    }

    public function delete($id)
    {
        $tasks = Task::findOrFail($id);
        $tasks->delete();

        return response()->json(null, 204);
    }
}

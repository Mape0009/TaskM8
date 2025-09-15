<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TaskParticipant;
use Illuminate\Support\Facades\Auth;

class TaskParticiantController extends Controller
{
    public function index()
    {
        $participants = TaskParticipant::all();
        return response()->json($participants);
    }

    public function show($id)
    {
        $participant = TaskParticipant::findOrFail($id);
        return response()->json($participant);
    }

    public function delete($id)
    {
        $participant = TaskParticipant::findOrFail($id);
        $participant->delete();
        return response()->json(['message' => 'Participant deleted successfully']);
    }

    public function join(Request $request, $taskId)
    {
        $request->validate([]);
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        TaskParticipant::firstOrCreate(
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
        TaskParticipant::where('taskId', $taskId)->where('userId', $userId)->delete();
        return redirect()->back()->with('success', 'you are not participating in the task.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Shift;
use Illuminate\Support\Facades\Auth;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Http\RolePermissions\Permissions;

class ShiftController extends Controller
{
    public function index($id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'view-shift')) {
            abort(403, 'Ikke tilladt.');
        }
        $shifts = Shift::all();
        return response()->json($shifts);
    }

    public function show($id)
    {
        $shift = Shift::findOrFail($id);
        return response()->json($shift);
    }

    public function delete($id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'delete-shift')) {
            abort(403, 'Ikke tilladt.');
        }
        $shift = Shift::findOrFail($id);
        $shift->delete();
        return response()->json(['message' => 'Shift deleted successfully']);
    }

    public function create(Request $request, $id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'create-shift')) {
            abort(403, 'Ikke tilladt.');
        }

        $shift = new Shift();
        $shift->taskId = $request->input('taskId');
        $shift->userId = $request->input('userId');
        $shift->startTime = $request->input('startTime');
        $shift->endTime = $request->input('endTime');
        $shift->save();

        return response()->json(['message' => 'Shift created successfully', 'shift' => $shift]);
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
        Shift::where('taskId', $taskId)->where('userId', $userId)->delete();
        return redirect()->back()->with('success', 'you are not participating in the task.');
    }
}
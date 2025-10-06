<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\EventRole;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Http\RolePermissions\Permissions;

class EventParticipantController extends Controller
{
    public function index($eventId)
    {
        $currentUser = auth()->user();
        $participant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $participant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'view-participants')) {
            abort(403, 'You do not have permission to view participants.');
        }
        $participants = EventParticipant::where('eventId', $eventId)->with(['user', 'event'])->get();
        $eventRole = EventRole::class;

        if (! $participant) {
            abort(403, 'You do not have access to this event.');
        }

        return view('organizerOverview', compact('participants', 'eventId', 'currentUser', 'eventRole'));
    }

    public function show($id)
    {
        $participant = EventParticipant::findOrFail($id);
        return response()->json($participant);
    }

    public function delete($id)
    {
        $currentUser = auth()->user();
        $participantToDelete = EventParticipant::findOrFail($id);
        $eventId = $participantToDelete->eventId;
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';

        if (!Permissions::hasPermission($role, 'delete-participant')) {
            abort(403, 'You do not have permission to delete participants.');
        }

        if ($participantToDelete->eventRole === EventRole::owner->name) {
            abort(403, 'Owners cannot be deleted.');
        }

        $currentRole = $role;
        $targetRole = $participantToDelete->eventRole;
        if ($currentRole === EventRole::coOwner->name && $targetRole === EventRole::coOwner->name) {
            abort(403, 'Co-Owners cannot delete other Co-Owners.');
        }
        $participantToDelete->delete();
        return response()->json(['message' => 'Participant deleted successfully']);
    }

    public function join(Request $request, $eventId)
    {
        $request->validate([]);
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        EventParticipant::firstOrCreate(
            ['eventId' => $eventId, 'userId' => $userId],
            ['status' => 'accepted']
        );
        return redirect()->back()->with('success', 'Du deltager i begivenheden.');
    }

    public function decline(Request $request, $eventId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        EventParticipant::where('eventId', $eventId)->where('userId', $userId)->delete();
        return redirect()->back()->with('success', 'Du deltager ikke i begivenheden.');
    }

    public function roleUpdate(Request $request, $participantId)
    {
        $request->validate([
            'eventRole' => 'required|in:owner,coOwner,taskManager,taskWorker,participant',
        ]);
        $participant = EventParticipant::findOrFail($participantId);
        $participant->eventRole = $request->input('eventRole');
        $participant->save();
        return redirect()->back()->with('success', 'Deltagerrollen er opdateret.');
    }

    public function rsvp(Request $request, $eventId)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        if ($request->input('status') === 'accepted') {
            $event = Event::find($eventId);
            if ($event && $event->participantLimit) {
                $acceptedCount = EventParticipant::where('eventId', $eventId)->where('status', 'accepted')->count();
                if ($acceptedCount >= $event->participantLimit && !EventParticipant::where('eventId', $eventId)->where('userId', $userId)->where('status', 'accepted')->exists()) {
                    return redirect()->back()->with('success', 'Begivenheden er fuld.');
                }
            }
        }
        if ($request->input('status') === 'accepted') {
            // Update existing row (pending/whatever) to accepted, or create if missing
            EventParticipant::updateOrCreate(
                ['eventId' => $eventId, 'userId' => $userId],
                ['status' => 'accepted']
            );
            return redirect()->back()->with('success', 'Din deltagelse er gemt.');
        }
        EventParticipant::updateOrCreate(
            ['eventId' => $eventId, 'userId' => $userId],
            ['status' => 'declined']
        );
        return redirect()->back()->with('success', 'Din deltagelse er opdateret.');
    }
}
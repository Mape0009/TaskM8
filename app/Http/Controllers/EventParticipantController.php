<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\EventRole;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventParticipantController extends Controller
{
    public function index($eventId)
    {
    $participants = EventParticipant::where('eventId', $eventId)->with(['user', 'event'])->get();
    $currentUser = auth()->user();
    $eventRole = EventRole::class;
    return view('organizerOverview', compact('participants', 'eventId', 'currentUser', 'eventRole'));
    }

    public function show($id)
    {
        $participant = EventParticipant::findOrFail($id);
        return response()->json($participant);
    }

    public function delete($id)
    {
        $participant = EventParticipant::findOrFail($id);
        $participant->delete();
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
        return redirect()->back();
    }

    public function decline(Request $request, $eventId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        EventParticipant::where('eventId', $eventId)->where('userId', $userId)->delete();
        return redirect()->back();
    }

    public function roleUpdate(Request $request, $participantId)
    {
        $request->validate([
            'eventRole' => 'required|in:owner,coOwner,taskManager,taskWorker,participant',
        ]);
        $participant = EventParticipant::findOrFail($participantId);
        $participant->eventRole = $request->input('eventRole');
        $participant->save();
        return redirect()->back();
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
            EventParticipant::updateOrCreate(
                ['eventId' => $eventId, 'userId' => $userId],
                ['status' => 'accepted']
            );
            return redirect()->back();
        }
        EventParticipant::updateOrCreate(
            ['eventId' => $eventId, 'userId' => $userId],
            ['status' => 'declined']
        );
         return redirect()->back();
    }
}
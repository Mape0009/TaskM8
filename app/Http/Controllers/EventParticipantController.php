<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

class EventParticipantController extends Controller
{
    public function index()
    {
        $participants = EventParticipant::all();
        return response()->json($participants);
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
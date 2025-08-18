<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Event;

class EventController extends Controller
{
    public function index()
    {
        $userId = auth()->user()->id;

        $ownedEvents = Event::where('ownerId', $userId);

        $participantEventIds = EventParticipant::where('userId', $userId)->pluck('eventId');
        $participatedEvents = Event::whereIn('id', $participantEventIds);

        $events = $ownedEvents->union($participatedEvents)->get();

        return view('events', compact('events'));
    }

    public function show($id)
    {
        $event = Event::findOrFail($id);
        return view('event', compact('event'));
    }

    public function create(Request $request)
    {
        $event = new Event();
        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->ownerId = auth()->user()->id;
        $event->save();
        
        return redirect('/dashboard')->with('success', 'Event er nu lavet!');
    }

    public function clearSuccessMessage()
    {
        session()->forget('success');
        return response()->json(['message' => 'Success message cleared']);
    }

    public function update(Request $request, $id)
    {
        $event = Event::findOrFail($id);
        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->save();
        return response()->json($event);
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);
        $event->delete();
        return response()->json(null, 204);
    }
}

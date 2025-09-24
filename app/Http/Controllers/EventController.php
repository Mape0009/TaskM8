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
        $request->validate([
            'eventName' => 'required|string|max:255',
            'startDate' => 'required|date',
            'endDate' => 'required|date|after_or_equal:startDate',
            'description' => 'nullable|string|max:800',
            'location' => 'nullable|string|max:255',
            'participantLimit' => 'nullable|integer|min:1',
            'repeat' => 'nullable|string|max:255',
        ]);
        $event = new Event();
        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->ownerId = auth()->user()->id;
        $event->participantLimit = $request->input('participantLimit');
        $event->repeat = $request->input('repeat') === 'on' ? ($request->input('repeat_interval') === 'custom' ? ($request->input('custom_interval') ?: 'Tilpasset') : $request->input('repeat_interval')) : null;
        $event->save();
        
        $eventParticipant = new EventParticipant();
        $eventParticipant->eventId = $event->id;
        $eventParticipant->userId = auth()->user()->id;
        $eventParticipant->status = 'accepted';
        $eventParticipant->eventRole = 'owner';
        $eventParticipant->save();

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
        // Enforce ownership
        if ($event->ownerId !== auth()->id()) {
            abort(403, 'Ikke tilladt.');
        }
        $request->validate([
            'eventName' => 'sometimes|string|max:255',
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date|after_or_equal:startDate',
            'description' => 'nullable|string|max:800',
            'location' => 'nullable|string|max:255',
            'repeat' => 'nullable|string|max:255',
        ]);
        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->repeat = $request->input('repeat') === 'on' ? ($request->input('repeat_interval') === 'custom' ? ($request->input('custom_interval') ?: 'Tilpasset') : $request->input('repeat_interval')) : null;
        $event->save();
        return response()->json($event);
    }

    public function edit($id)
    {
        $event = Event::findOrFail($id);
        // Enforce ownership
        if ($event->ownerId !== auth()->id()) {
            abort(403, 'Ikke tilladt.');
        }
        return view('events.edit', compact('event'));
    }

    public function delete($id)
    {
        $event = Event::findOrFail($id);
        // Enforce ownership
        if ($event->ownerId !== auth()->id()) {
            abort(403, 'Ikke tilladt.');
        }
        $event->delete();
        return redirect('/events')->with('success', 'Begivenheden er slettet.');
    }
}

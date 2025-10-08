<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Event;
use App\Http\RolePermissions\Permissions;
use App\Enums\EventRole;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        if (!auth()->check()) {
            return redirect('/login');
        }

        $user = auth()->user();
        $userId = $user->id;
        $events = $this->getEventsForUser($userId);

        return view('events', ['events' => $events]);
    }

    /**
     * Return events visible to a given user (used by events.index and dashboard).
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getEventsForUser(int $userId)
    {
        $participantEventIds = EventParticipant::where('userId', $userId)->pluck('eventId');
        $events = Event::whereIn('id', $participantEventIds)->get();

        // Preload the user's EventParticipant records for these events to avoid N+1 queries
        $eventIds = $events->pluck('id')->all();
        $participantMap = EventParticipant::whereIn('eventId', $eventIds)
            ->where('userId', $userId)
            ->get()
            ->keyBy('eventId');

        // Filter events by per-event permission
        $filtered = $events->filter(function ($event) use ($participantMap) {
            $participant = $participantMap->get($event->id);
            $role = $participant?->eventRole ?? 'participant';
            return Permissions::hasPermission($role, 'view-event');
        })->values();

        return $filtered;
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
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'participantLimit' => 'nullable|integer|min:1',
        ]);
        $event = new Event();
        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->ownerId = auth()->user()->id;
        $event->participantLimit = $request->input('participantLimit');
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
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'edit-event')) {
            abort(403, 'You do not have permission to edit this event.');
        }

        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->save();
        return response()->json($event);
    }

    public function edit($id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        // Enforce ownership
        if (!Permissions::hasPermission($role, 'edit-event')) {
            abort(403, 'Ikke tilladt.');
        }  

        $event = Event::findOrFail($id);
        return view('events.edit', compact('event'));
    }

    public function delete($id)
    {
        $user = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $id)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = Event::findOrFail($id);
        if (!Permissions::hasPermission($role, 'delete-event')) {
            abort(403, 'Ikke tilladt.');
        }  

        $event = Event::findOrFail($id);
        $event->delete();
        return redirect('/events')->with('success', 'Begivenheden er slettet.');
    }
}

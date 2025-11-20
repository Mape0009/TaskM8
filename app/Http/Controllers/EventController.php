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
            return redirect('/signin');
        }

        $user = auth()->user();
        $userId = $user->id;
        $events = $this->getEventsForUser($userId)->sortByDesc('startDate')->values();
        $participant = EventParticipant::where('userId', $userId)->get();

        return view('events.index', compact('events', 'participant'));
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
        $events = Event::whereIn('id', $participantEventIds)
            ->where('endDate', '>=', now())
            ->get();

        $eventIds = $events->pluck('id')->all();
        $participantMap = EventParticipant::whereIn('eventId', $eventIds)
            ->where('userId', $userId)
            ->get()
            ->keyBy('eventId');

        $filtered = $events->filter(function ($event) use ($participantMap) {
            $participant = $participantMap->get($event->id);
            $role = $participant?->eventRole ?? 'participant';
            return Permissions::hasPermission($role, 'view-event');
        })->values();

        return $filtered;
    }

    /**
     * Return previous (ended) events visible to a given user.
     *
     * @param int $userId
     * @return \Illuminate\Support\Collection
     */
    public function getPreviousEventsForUser(int $userId)
    {
        $participantEventIds = EventParticipant::where('userId', $userId)->pluck('eventId');
        $events = Event::whereIn('id', $participantEventIds)
            ->where(function($q){
                $q->where('endDate', '<', now());
            })
            ->get();

        $eventIds = $events->pluck('id')->all();
        $participantMap = EventParticipant::whereIn('eventId', $eventIds)
            ->where('userId', $userId)
            ->get()
            ->keyBy('eventId');

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
        return view('events.show', compact('event'));
    }

    public function json($id)
    {
        if (!auth()->check()) { abort(403); }
        $userId = auth()->id();
        $participant = EventParticipant::where('eventId', $id)->where('userId', $userId)->first();
        $role = $participant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'view-event')) {
            abort(403);
        }
        $event = Event::findOrFail($id);
        return response()->json([
            'id' => $event->id,
            'eventName' => $event->eventName,
            'location' => $event->location,
            'description' => $event->description,
            'startDate' => $event->startDate,
            'endDate' => $event->endDate,
            'participantLimit' => $event->participantLimit,
        ]);
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

        return redirect('/dashboard');
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
        $request->validate([
            'eventName' => 'sometimes|string|max:255',
            'startDate' => 'sometimes|date',
            'endDate' => 'sometimes|date|after_or_equal:startDate',
            'description' => 'nullable|string|max:800',
            'location' => 'nullable|string|max:255',
        ]);
        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->save();
        return redirect('/dashboard');
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
        return redirect('/events');
    }
}

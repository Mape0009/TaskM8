<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::query()
            ->when(Auth::check(), function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->orderBy('startDate', 'desc')
            ->get();
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
        $event->user_id = Auth::id();
        $event->save();
        
        // Redirect til dashboard med success-besked og fjern den straks
        return redirect('/dashboard')->with('success', 'Event er nu lavet!');
    }

    public function clearSuccessMessage()
    {
        session()->forget('success');
        return response()->json(['message' => 'Success message cleared']);
    }

    public function edit($id)
    {
        $event = Event::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        return view('events.edit', compact('event'));
    }

    public function update(Request $request, $id)
    {
        $event = Event::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $event->eventName = $request->input('eventName');
        $event->startDate = $request->input('startDate');
        $event->endDate = $request->input('endDate');
        $event->description = $request->input('description');
        $event->location = $request->input('location');
        $event->save();
        return redirect('/events/'.$event->id)->with('success', 'Begivenheden er opdateret.');
    }

    public function delete($id)
    {
        $event = Event::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();
        $event->delete();
        return response()->json(null, 204);
    }
}

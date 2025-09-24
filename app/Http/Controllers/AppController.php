<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EventController;
use App\Models\Event;
use App\Models\EventParticipant;
use App\Models\Shift;

class AppController extends Controller
{
    /**
     * Get all events.
     */
    public function eventGet()
    {
        $events = Event::all();
        return response()->json($events);
    }

    /**
     * Get all event participants.
     */
    public function eventParticipantGet()
    {
        $participants = EventParticipant::all();
        return response()->json($participants);
    }

    /**
     * Get all shifts.
     */
    public function shiftGet()
    {
        $shifts = Shift::all();
        return response()->json($shifts);
    }
}

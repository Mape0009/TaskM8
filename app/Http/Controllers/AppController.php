<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\EventController;
use App\Models\Event;

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
}

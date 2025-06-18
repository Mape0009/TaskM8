<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;

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
}
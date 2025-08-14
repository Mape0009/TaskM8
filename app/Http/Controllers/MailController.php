<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Mail\EventInvite;

class MailController extends Controller
{
    public function sendEventInvites(Request $request)
    {
        $emails = $request->input('emailsInvite', []);
        $eventId = $request->input('eventIdInvite');

        $eventData = [
            'id' => $eventId,
        ];

        foreach ($emails as $email) {
            EventInvite::sendMail($email, $eventData);
        }

        return response()->json(['success' => true]);
    }
}

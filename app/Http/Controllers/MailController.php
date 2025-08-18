<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Mail\EventInvite;
use App\Models\User;

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
            if (User::where('email', $email)->exists()) {
                EventInvite::sendExistingUserMail($email, $eventData);
            }
            else {
                EventInvite::sendNewUserMail($email, $eventData);
            }
        }

        return response()->json(['success' => true]);
    }

    public static function sendNewUserMail($recipientEmail, $eventData)
    {
        Mail::to($recipientEmail)->send(new self($eventData));
    }

    public static function sendExistingUserMail($recipientEmail, $eventData)
    {
        Mail::to($recipientEmail)->send(new self($eventData));
    }
}

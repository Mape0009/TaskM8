<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EventInvite;
use App\Models\User;
use App\Models\Event;

class MailController extends Controller
{
    public static function sendNewUserMail($recipientEmail, $eventData)
    {
        Mail::to($recipientEmail)->send(new EventInvite($eventData));
    }

    public static function sendExistingUserMail($recipientEmail, $eventData)
    {
        Mail::to($recipientEmail)->send(new EventInvite($eventData));
    }

    /*
    public function sendEventInvites(Request $request)
    {
        Log::info('MailController@sendEventInvites was called');
        $emails = $request->input('emailsInvite', []);
        $eventId = $request->input('eventIdInvite');

        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $inviter = $request->user();
        $inviterEmail = $inviter ? $inviter->email : null;

        $eventData = [
            'id' => $event->id,
            'title' => $event->eventName ?? '',
            'location' => $event->location ?? '',
            'time' => $event->start_time ?? '',
            'end_time' => $event->end_time ?? '',
            'description' => $event->description ?? '',
            'inviter_email' => $inviterEmail,
        ];


        // Log the event data and recipient emails
        Log::info('Sending event invites', [
            'eventData' => $eventData,
            'recipients' => $emails,
        ]);

        foreach ($emails as $email) {
            if (User::where('email', $email)->exists()) {
                MailController::sendExistingUserMail($email, $eventData);
            } else {
                MailController::sendNewUserMail($email, $eventData);
            }
        }

        return response()->json(['success' => true]);
    }
    */

     public function sendEventInvites(Request $request)
    {
        Log::info('MailController@sendEventInvites was called');
        $emails = $request->input('emailsInvite', []);
        $eventId = $request->input('eventIdInvite');

        $event = Event::find($eventId);
        if (!$event) {
            return response()->json(['success' => false, 'message' => 'Event not found'], 404);
        }

        $inviter = $request->user();
        $inviterEmail = $inviter ? $inviter->email : null;

        $eventData = [
            'id' => $event->id,
            'title' => $event->eventName ?? '',
            'location' => $event->location ?? '',
            'time' => $event->startDate ?? '',
            'end_time' => $event->endDate ?? '',
            'description' => $event->description ?? '',
            'inviter_email' => $inviterEmail,
        ];


        // Log the event data and recipient emails
        Log::info('Sending event invites', [
            'eventData' => $eventData,
            'recipients' => $emails,
        ]);

            if (User::where('email', $emails)->exists()) {
                MailController::sendExistingUserMail($emails, $eventData);
            } else {
                MailController::sendNewUserMail($emails, $eventData);
            }

        return response()->json(['success' => true]);
    }
}

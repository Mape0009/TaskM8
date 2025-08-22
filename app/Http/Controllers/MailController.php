<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EventInvite;
use App\Models\User;
use App\Models\Event;
use App\Http\Controllers\PinCodeController;

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

        $pinCodeController = new PinCodeController();
        foreach ($emails as $email) {
            $pinCodeResponse = $pinCodeController->generatePinCode($request);
            $pinCodeData = $pinCodeResponse->getData(true);
            $eventData['pin_code'] = $pinCodeData['pincode'] ?? null;
            if (User::where('email', $email)->exists()) {
                MailController::sendExistingUserMail($email, $eventData);
            } else {
                MailController::sendNewUserMail($email, $eventData);
            }
        }

        return response()->json(['success' => true]);
    }
}

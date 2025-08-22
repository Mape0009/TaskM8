<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EventInvite;
use App\Models\User;
use App\Models\Event;
use App\Models\PinCode;
use App\Models\EventParticipant;

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
            return redirect()->back()->with('success', 'Kunne ikke finde begivenheden.');
        }

        $inviter = $request->user();
        $inviterEmail = $inviter ? $inviter->email : null;

        $eventDataBase = [
            'id' => $event->id,
            'title' => $event->eventName ?? '',
            'location' => $event->location ?? '',
            'time' => $event->startDate ?? '',
            'end_time' => $event->endDate ?? '',
            'description' => $event->description ?? '',
            'inviter_email' => $inviterEmail,
        ];

        Log::info('Sending event invites', [
            'eventId' => $event->id,
            'recipients' => $emails,
        ]);

        foreach ($emails as $email) {
            $existingUser = User::where('email', $email)->first();

            if ($existingUser) {
                // Existing user: do not send pin; just add as participant (pending)
                EventParticipant::updateOrCreate(
                    ['eventId' => $event->id, 'userId' => $existingUser->id],
                    ['status' => 'pending']
                );
                continue;
            }

            // New user: generate PIN and send invite link
            $pinCode = str_pad((string)random_int(0, 9999), 4, '0', STR_PAD_LEFT);

            PinCode::create([
                'pincode' => $pinCode,
                'email' => $email,
                'eventId' => $event->id,
                'createdAt' => now(),
            ]);

            $eventData = $eventDataBase;
            $eventData['pin_code'] = $pinCode;
            $eventData['invite_email'] = $email;
            $payload = base64_encode(json_encode([
                'email' => $email,
                'pin' => $pinCode,
                'event' => $event->id,
                'ts' => now()->timestamp,
            ]));
            $eventData['invite_url'] = url('/signup') . '?token=' . urlencode($payload);

            self::sendNewUserMail($email, $eventData);
        }

        return redirect()->back()->with('success', 'Invitationen er sendt.');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\RolePermissions\Permissions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;
use App\Mail\EventInvite;
use App\Mail\ExistingUserInvite;
use App\Models\User;
use App\Models\Event;
use App\Models\PinCode;
use App\Models\EventParticipant;
use App\Models\Mail as MailModel;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\Notifications\NotificationMessages;

class MailController extends Controller
{
    public static function sendNewUserMail($recipientEmail, $eventData, $id = null)
    {
        $user = auth()->user();
        $eventId = $id ?? ($eventData['id'] ?? null);
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = $eventId ? Event::findOrFail($eventId) : null;
        if (!Permissions::hasPermission($role, 'manage-invites')) {
            abort(403, 'Ikke tilladt.');
        }
        Mail::to($recipientEmail)->send(new EventInvite($eventData));
    }

    public static function sendExistingUserMail($recipientEmail, $eventData, $id = null)
    {
        $user = auth()->user();
        $eventId = $id ?? ($eventData['id'] ?? null);
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $user?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';
        $event = $eventId ? Event::findOrFail($eventId) : null;
        if (!Permissions::hasPermission($role, 'manage-invites')) {
            abort(403, 'Ikke tilladt.');
        }
        // For existing users we should send the ExistingUserInvite mailable
        Mail::to($recipientEmail)->send(new ExistingUserInvite($eventData));
    }

    public function sendEventInvites(Request $request)
    {
        Log::info('MailController@sendEventInvites was called');
        // Validate incoming emails array with regex
        $request->validate([
            'emailsInvite' => 'required|array|min:1',
            'emailsInvite.*' => ['required','regex:/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/i'],
        ]);

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
                // Existing user: add as participant (pending) and notify without pin
                EventParticipant::updateOrCreate(
                    ['eventId' => $event->id, 'userId' => $existingUser->id],
                    ['status' => 'pending']
                );
                $eventData = $eventDataBase;
                $eventData['invite_email'] = $email;
                Mail::to($email)->send(new ExistingUserInvite($eventData));
                $notificationController = new NotificationController();
                $notificationController->dispatchNotification(
                    $existingUser->id,
                    (int) $event->id,
                    NotificationMessages::EVENT_INVITED,
                    'eventInvitationSystemNotifications'
                );
                // Record mail for previous invitees list
                if ($inviter) {
                    MailModel::create([
                        'subject' => 'Event Invitation',
                        'body' => $eventData['description'] ?? '',
                        'senderId' => $inviter->id,
                        'recipientId' => $existingUser->id,
                        'sentAt' => now(),
                    ]);
                }
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

        return redirect()->back();
    }

    public function getPreviousInvitees(Request $request, $eventId)
    {
        $user = $request->user();
        if (!$user) {
            return response()->json([], 401);
        }
        $rows = MailModel::where('senderId', $user->id)
            ->orderBy('sentAt', 'desc')
            ->with('recipient')
            ->get()
            ->unique('recipientId')
            ->take(50);
        $result = $rows->map(function ($m) {
            return [
                'id' => $m->recipientId,
                'name' => optional($m->recipient)->name,
                'email' => optional($m->recipient)->email,
            ];
        })->filter(function ($i) {
            return !empty($i['email']);
        })->values();
        return response()->json($result);
    }
}
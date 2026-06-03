<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationSettings;
use App\Http\Controllers\Notifications\NotificationMessages;

class NotificationController extends Controller
{
    private const SETTINGS_FIELDS = [
        'newEventSystemNotifications',
        'newShiftSystemNotifications',
        'participantLeaveSystemNotifications',
        'employeeLeaveSystemNotifications',
        'eventDeletedSystemNotifications',
        'eventInvitationSystemNotifications',
        'groupInvitationSystemNotifications',
        'newEventEmailNotifications',
        'newShiftEmailNotifications',
        'participantLeaveEmailNotifications',
        'employeeLeaveEmailNotifications',
        'eventDeletedEmailNotifications',
        'eventInvitationEmailNotifications',
        'groupInvitationEmailNotifications',
    ];

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserId = auth()->id();
        $notifications = Notification::with('event:id,eventName')
            ->where('userId', $currentUserId)
            ->latest()
            ->take(5)
            ->get();

        $notifications = $notifications->map(function (Notification $notification): array {
            return [
                'id' => $notification->id,
                'userId' => $notification->userId,
                'eventId' => $notification->eventId,
                'message' => $this->localizedNotificationMessage($notification->message),
                'raw_message' => $notification->message,
                'isRead' => $notification->isRead,
                'created_at' => $notification->created_at?->toIso8601String(),
                'event' => $notification->event ? [
                    'id' => $notification->event->id,
                    'eventName' => $notification->event->eventName,
                ] : null,
            ];
        });

        return response()->json([
            'notifications' => $notifications,
            'unreadCount' => Notification::where('userId', $currentUserId)->where('isRead', false)->count(),
        ]);
    }

    public function notificationsCount()
    {
        $currentUserId = auth()->id();
        $unreadCount = Notification::where('userId', $currentUserId)->where('isRead', false)->count();
        return response()->json(['unreadCount' => $unreadCount]);
    }

    public function sendNotification(Request $request, string $userId, string $eventId)
    {
        $validated = $request->validate([
            'message' => ['nullable', 'string', 'max:1000'],
            'type' => ['nullable', 'string', 'max:100'],
        ]);

        $message = $validated['message'] ?? match ($validated['type'] ?? null) {
            'new-task-assigned' => NotificationMessages::NEW_TASK_ASSIGNED,
            'task-updated' => NotificationMessages::TASK_UPDATED,
            'task-deleted' => NotificationMessages::TASK_DELETED,
            'shift-assigned' => NotificationMessages::SHIFT_ASSIGNED,
            'shift-updated' => NotificationMessages::SHIFT_UPDATED,
            'shift-deleted' => NotificationMessages::SHIFT_DELETED,
            'event-updated' => NotificationMessages::EVENT_UPDATED,
            'event-deleted' => NotificationMessages::EVENT_DELETED,
            'participant-left' => NotificationMessages::PARTICIPANT_LEFT,
            'participant-joined' => NotificationMessages::PARTICIPANT_JOINED,
            'group-joined' => NotificationMessages::GROUP_JOINED,
            'group-left' => NotificationMessages::GROUP_LEFT,
            default => NotificationMessages::EVENT_UPDATED,
        };

        $notification = $this->dispatchNotification($userId, (int) $eventId, $message);

        return response()->json($notification, 201);
    }

    public function dispatchNotification(int|string $userId, int $eventId, string $message, ?string $settingField = null)
    {
        if ($settingField && ! $this->isNotificationEnabled($userId, $settingField)) {
            return null;
        }

        return Notification::create([
            'userId' => $userId,
            'eventId' => $eventId,
            'message' => $message,
            'isRead' => false,
        ]);
    }

    public function isNotificationEnabled(int|string $userId, string $settingField): bool
    {
        $settings = NotificationSettings::where('userId', $userId)->first();

        if (! $settings || ! in_array($settingField, self::SETTINGS_FIELDS, true)) {
            return false;
        }

        return (bool) $settings->{$settingField};
    }

    public function markAsRead(string $id)
    {
        $notification = Notification::whereKey($id)
            ->where('userId', auth()->id())
            ->firstOrFail();

        $notification->isRead = true;
        $notification->save();

        return response()->json([
            'message' => 'Notification marked as read',
            'unreadCount' => Notification::where('userId', auth()->id())->where('isRead', false)->count(),
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $notification = Notification::whereKey($id)
            ->where('userId', auth()->id())
            ->firstOrFail();

        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
    }

    public function autoDeleteOldNotifications()
    {
        $thresholdDate = now()->subMinutes(3);
        $deletedCount = Notification::where('isRead', true)
            ->where('created_at', '<', $thresholdDate)
            ->delete();

        return response()->json([
            'message' => 'Old notifications deleted successfully',
            'deletedCount' => $deletedCount,
        ]);
    }

    public function updateNotificationSettings(Request $request)
    {
        $currentUserId = auth()->id();
        $settings = NotificationSettings::updateOrCreate(
            ['userId' => $currentUserId],
            collect(self::SETTINGS_FIELDS)->mapWithKeys(function (string $field): array {
                return [$field => request()->boolean($field)];
            })->all()
        );

        if ($request->expectsJson()) {
            return response()->json($settings);
        }

        return back()->with('success', 'Notifikationsindstillinger er gemt.');
    }

    private function localizedNotificationMessage(string $message): string
    {
        $translationKey = match ($message) {
            NotificationMessages::NEW_TASK_ASSIGNED => 'ui.notification_message_new_task_assigned',
            NotificationMessages::TASK_UPDATED => 'ui.notification_message_task_updated',
            NotificationMessages::TASK_DELETED => 'ui.notification_message_task_deleted',
            NotificationMessages::SHIFT_ASSIGNED => 'ui.notification_message_shift_assigned',
            NotificationMessages::SHIFT_UPDATED => 'ui.notification_message_shift_updated',
            NotificationMessages::SHIFT_DELETED => 'ui.notification_message_shift_deleted',
            NotificationMessages::EVENT_UPDATED => 'ui.notification_message_event_updated',
            NotificationMessages::EVENT_DELETED => 'ui.notification_message_event_deleted',
            NotificationMessages::EVENT_INVITED => 'ui.notification_message_event_invited',
            NotificationMessages::PARTICIPANT_LEFT => 'ui.notification_message_participant_left',
            NotificationMessages::PARTICIPANT_JOINED => 'ui.notification_message_participant_joined',
            NotificationMessages::GROUP_INVITED => 'ui.notification_message_group_invited',
            NotificationMessages::GROUP_JOINED => 'ui.notification_message_group_joined',
            NotificationMessages::GROUP_LEFT => 'ui.notification_message_group_left',
            default => null,
        };

        return $translationKey ? __($translationKey) : $message;
    }
}
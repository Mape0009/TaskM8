<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;
use App\Models\NotificationSettings;
use App\Http\Controllers\Notifications\NotificationMessages;

class NotificationController extends Controller
{
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

        $notification = Notification::create([
            'userId' => $userId,
            'eventId' => $eventId,
            'message' => $message,
            'isRead' => false,
        ]);

        return response()->json($notification, 201);
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
            [
                'newEventSystemNotifications' => $request->boolean('newEventSystemNotifications'),
                'newShiftSystemNotifications' => $request->boolean('newShiftSystemNotifications'),
                'participantLeaveSystemNotifications' => $request->boolean('participantLeaveSystemNotifications'),
                'employeeLeaveSystemNotifications' => $request->boolean('employeeLeaveSystemNotifications'),
                'eventDeletedSystemNotifications' => $request->boolean('eventDeletedSystemNotifications'),
                'groupInvitationSystemNotifications' => $request->boolean('groupInvitationSystemNotifications'),
                'newEventEmailNotifications' => $request->boolean('newEventEmailNotifications'),
                'newShiftEmailNotifications' => $request->boolean('newShiftEmailNotifications'),
                'participantLeaveEmailNotifications' => $request->boolean('participantLeaveEmailNotifications'),
                'employeeLeaveEmailNotifications' => $request->boolean('employeeLeaveEmailNotifications'),
                'eventDeletedEmailNotifications' => $request->boolean('eventDeletedEmailNotifications'),
                'groupInvitationEmailNotifications' => $request->boolean('groupInvitationEmailNotifications'),
            ]
        );

        if ($request->expectsJson()) {
            return response()->json($settings);
        }

        return back()->with('success', 'Notifikationsindstillinger er gemt.');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Notification;

class NotificationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $currentUserId = auth()->id();
        $notifications = Notification::where('userId', $currentUserId)->get();
        return response()->json($notifications);
    }

    public function notificationsCount()
    {
        $currentUserId = auth()->id();
        $unreadCount = Notification::where('userId', $currentUserId)->where('isRead', false)->count();
        return response()->json(['unreadCount' => $unreadCount]);
    }

    public function sendNotification($userId, $eventId, $message)
    {
        $notification = Notification::create([
            'userId' => $userId,
            'eventId' => $eventId,
            'message' => $message,
        ]);

        return response()->json($notification, 201);
    }

    public function markAsRead(string $id)
    {
        $notification = Notification::findOrFail($id);
        $notification->isRead = true;
        $notification->save();
        return response()->json(['message' => 'Notification marked as read']);
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
        $notification = Notification::findOrFail($id);
        $notification->delete();
        return response()->json(['message' => 'Notification deleted successfully']);
    }

    public function autoDeleteOldNotifications()
    {
        $notifications = Notification::all();

        if ($notifications->isRead == true) {
            $thresholdDate = now()->subMinutes(3);
            Notification::where('isRead', true)->where('created_at', '<', $thresholdDate)->delete();
        }

         return response()->json(['message' => 'Old notifications deleted successfully']);
    }
}

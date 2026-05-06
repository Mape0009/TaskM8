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

    public function sendNotification(Request $request)
    {
        $request->validate([
            'userId' => 'required|exists:users,id',
            'eventId' => 'required|exists:events,id',
            'message' => 'required|string',
        ]);

        $notification = Notification::create([
            'userId' => $request->userId,
            'eventId' => $request->eventId,
            'message' => $request->message,
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
}

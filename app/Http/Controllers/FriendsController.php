<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Friends;

class FriendsController extends Controller
{
    public function sendFriendRequest(Request $request)
    {
        $request->validate([
            'friendId' => 'required|exists:users,id',
        ]);

        $friendship = Friends::create([
            'userId' => $request->user()->id,
            'friendId' => $request->input('friendId'),
        ]);

        return response()->json(['message' => 'Friend request sent'], 201);
    }

    public function removeFriend($id)
    {
        $friendship = Friends::where(function ($query) use ($id) {
            $query->where('userId', auth()->id())
                ->where('friendId', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('userId', $id)
                ->where('friendId', auth()->id());
        })->firstOrFail();

        $friendship->delete();

        return response()->json(['message' => 'Friend removed'], 200);
    }
}

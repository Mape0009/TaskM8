<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupMember;
use App\Models\User;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($groupId)
    {
        $groupMembers = GroupMember::where('groupId', $groupId)->get();
        return view('group.groupMembers', ['groupMembers' => $groupMembers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function invite(Request $request)
    {
        $request->validate([
            'userId' => 'required|array|min:1',
            'userId.*' => 'exists:users,id',
            'groupId' => 'required|exists:groups,id',
        ]);

        $userId = $request->input('userId', []);
        $groupId = $request->input('groupId');

        foreach ($userId as $id) {
            $existingMember = GroupMember::where('groupId', $groupId)
                ->where('userId', $id)
                ->first();
            if (!$existingMember) {
                $groupMember = new GroupMember();
                $groupMember->groupId = $groupId;
                $groupMember->userId = $id;
                $groupMember->save();
            }
        }
        return redirect()->back();
    }

    /**
     * Display the specified resource.
     */
    public function showUsers(string $id)
    {
        $users = User::whereNotIn('id', function ($query) use ($id) {
            $query->select('userId')
                ->from('group_members')
                ->where('groupId', $id);
        })->get();

        return view('group.groupInvite', ['users' => $users, 'groupId' => $id]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function edit(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id)
    {
        $groupMember = GroupMember::find($id);
        if (!$groupMember) {
            return response()->json(['message' => 'Group member not found'], 404);
        }
        $groupMember->delete();
        return redirect()->back();
    }

    public function leaveGroup($groupId)
    {
        $userId = auth()->user()->id;
        $groupMember = GroupMember::where('groupId', $groupId)
            ->where('userId', $userId)
            ->first();
        if (!$groupMember) {
            return response()->json(['message' => 'Group member not found'], 404);
        }

        $groupMember->delete();
        return redirect()->back();
    }
}
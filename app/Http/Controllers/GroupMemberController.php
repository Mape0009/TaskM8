<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GroupMember;

class GroupMemberController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index($groupId)
    {
        $groupMembers = GroupMember::all()->where('groupId', '=', $groupId);
        return view('group.groupMembers', ['groupMembers' => $groupMembers]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function invite(Request $request)
    {
        //
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

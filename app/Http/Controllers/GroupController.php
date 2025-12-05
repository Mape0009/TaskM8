<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMember;
use Illuminate\Support\Facades\Auth;

class GroupController extends Controller
{
    public function index()
    {
        $publicGroups = Group::where('private', false)->get();

        $myGroups = collect();
        if (Auth::check()) {
            $userId = Auth::id();
            $myGroupIds = GroupMember::where('userId', $userId)->pluck('groupId');

            $myGroups = Group::whereIn('id', $myGroupIds)->get();
        }

        $visibleGroups = $publicGroups
            ->merge($myGroups)
            ->unique('id')
            ->values();

        return view('group.groupOverview', [
            'allGroups' => $visibleGroups,
            'myGroups' => $myGroups,
            'isAuthenticated' => Auth::check(),
        ]);
    }

    public function create(Request $request)
    {
        $group = new Group();
        $group->groupName = $request->input('groupName');
        $group->description = $request->input('description');
        $group->private = $request->boolean('private');
        $group->save();

        if (Auth::check()) {
            GroupMember::firstOrCreate([
                'groupId' => $group->id,
                'userId' => Auth::id(),
            ]);
        }

        return redirect()->route('groups.overview');
    }

    public function show($id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        return response()->json($group);
    }

    public function edit($id, Request $request)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        $group->groupName = $request->input('groupName', $group->groupName);
        $group->description = $request->input('description', $group->description);
        $group->private = $request->boolean('private', $group->private);
        $group->save();
        return response()->json($group);
    }

    public function delete($id)
    {
        $group = Group::find($id);
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }
        $group->delete();
        return redirect()->route('groups.overview');
    }
}

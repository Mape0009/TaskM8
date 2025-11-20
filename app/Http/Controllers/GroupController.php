<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;

class GroupController extends Controller
{
    public function index()
    {
        $groups = Group::all()->where('private', false);
        return view('group.groupOverview', ['groups' => $groups]);
    }

    public function create(Request $request)
    {
        $group = new Group();
        $group->groupName = $request->input('groupName');
        $group->description = $request->input('description');
        $group->private = $request->boolean('private');
        $group->save();

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

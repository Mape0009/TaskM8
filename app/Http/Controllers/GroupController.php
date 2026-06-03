<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\GroupMember;
use App\Models\PinCode;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Notifications\NotificationMessages;
use App\Http\Controllers\NotificationController;
use App\Models\Notification;
use Illuminate\Support\Facades\Mail;
use App\Mail\GroupInviteExistingUser;
use App\Mail\GroupInviteNewUser;

class GroupController extends Controller
{
    public function index()
    {
        $publicGroups = Group::where('private', false)->get();

        $myGroups = collect();
        $myGroupIds = collect();
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
            'myGroupIds' => $myGroupIds,
        ]);
    }

    public function create(Request $request)
    {
        if (!Auth::check()) {
            return redirect('/signin');
        }

        $request->validate([
            'groupName' => 'required|string|max:255',
            'description' => 'nullable|string|max:240',
            'private' => 'sometimes|boolean',
        ]);

        $group = new Group();
        $group->groupName = $request->input('groupName');
        $group->description = $request->input('description');
        $group->private = $request->boolean('private');
        $group->ownerId = Auth::id();
        $group->save();

        GroupMember::firstOrCreate([
            'groupId' => $group->id,
            'userId' => Auth::id(),
        ]);

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
        if (!Auth::check()) {
            abort(403);
        }

        $group = Group::find($id);
        if (!$group) {
            return response()->json(['message' => 'Group not found'], 404);
        }

        if ($group->ownerId !== Auth::id()) {
            abort(403, 'Kun gruppeejeren kan slette denne gruppe.');
        }

        $group->delete();
        return redirect()->route('groups.overview');
    }

    public function leave($id)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $group = Group::findOrFail($id);
        $userId = Auth::id();

        // Owner should delete instead of leave (or transfer ownership if feature exists)
        if ($group->ownerId === $userId) {
            abort(403, 'Ejeren kan ikke forlade gruppen. Slet gruppen i stedet.');
        }

        GroupMember::where('groupId', $group->id)
            ->where('userId', $userId)
            ->delete();

        return redirect()->route('groups.overview');
    }

    public function members($id)
    {
        if (!Auth::check()) {
            abort(403);
        }

        $userId = Auth::id();
        $group = Group::findOrFail($id);

        // Brugere må kun invitere grupper, de selv er medlem af
        $isMember = GroupMember::where('groupId', $group->id)
            ->where('userId', $userId)
            ->exists();

        if (!$isMember) {
            abort(403);
        }

        $members = GroupMember::where('groupId', $group->id)
            ->with('user')
            ->get()
            ->map(function ($member) {
                return [
                    'id' => $member->user?->id,
                    'name' => $member->user?->name,
                    'email' => $member->user?->email,
                ];
            })
            ->filter(fn ($m) => !empty($m['email']))
            ->values();

        return response()->json($members);
    }

    public function manage($id)
    {
        $group = Group::findOrFail($id);
        if (!Auth::check() || $group->ownerId !== Auth::id()) {
            abort(403);
        }

        $members = GroupMember::where('groupId', $group->id)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();

        return view('group.manageMembers', compact('group', 'members'));
    }

    public function addMember(Request $request, $id)
    {
        $group = Group::findOrFail($id);
        if (!Auth::check() || $group->ownerId !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'email' => 'required|email',
        ]);

        $email = trim(mb_strtolower((string)$request->input('email')));
        $user = User::whereRaw('LOWER(email) = ?', [$email])->first();

        $groupData = [
            'id' => $group->id,
            'name' => $group->groupName ?? '',
            'description' => $group->description ?? '',
            'group_url' => url('/groups/overview'),
            'invite_email' => $email,
        ];

        if ($user) {
            GroupMember::firstOrCreate([
                'groupId' => $group->id,
                'userId' => $user->id,
            ]);

            $notificationController = new NotificationController();
            $notificationController->dispatchNotification(
                $user->id,
                0,
                NotificationMessages::GROUP_INVITED,
                'groupInvitationSystemNotifications'
            );

            Mail::to($email)->send(new GroupInviteExistingUser($groupData));
            return redirect()->back();
        }

        $pinCode = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);

        PinCode::create([
            'pincode' => $pinCode,
            'email' => $email,
            'groupId' => $group->id,
            'createdAt' => now(),
        ]);

        // Notify the user that they have been added to the group
        $notificationController = new NotificationController();
        $notificationController->sendNotification(
            $user->id,
            $group->id,
            NotificationMessages::GROUP_JOINED
        );
        $payload = base64_encode(json_encode([
            'email' => $email,
            'pin' => $pinCode,
            'group' => $group->id,
            'ts' => now()->timestamp,
        ]));

        $groupData['pin_code'] = $pinCode;
        $groupData['invite_url'] = url('/signup') . '?token=' . urlencode($payload);

        Mail::to($email)->send(new GroupInviteNewUser($groupData));

        return redirect()->back();
    }

    public function removeMember($groupId, $memberId)
    {
        $group = Group::findOrFail($groupId);
        if (!Auth::check() || $group->ownerId !== Auth::id()) {
            abort(403);
        }

        $member = GroupMember::where('groupId', $group->id)->where('id', $memberId)->firstOrFail();
        if ($member->userId === $group->ownerId) {
            abort(403, 'Du kan ikke fjerne dig selv som ejer.');
        }

        $member->delete();
        return redirect()->back();
    }

    public function join($id)
    {
        if (!Auth::check()) {
            return redirect('/signin');
        }

        $group = Group::findOrFail($id);
        if ($group->private) {
            abort(403, 'Kun ejeren kan invitere til en privat gruppe.');
        }

        GroupMember::firstOrCreate([
            'groupId' => $group->id,
            'userId' => Auth::id(),
        ]);

        return redirect()->route('groups.overview');
    }
}
<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EventParticipant;
use App\Models\EventRole;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use App\Http\RolePermissions\Permissions;

class EventParticipantController extends Controller
{
    public function index($eventId)
    {
        $currentUser = auth()->user();
        $participant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $participant?->eventRole ?? 'participant';
        if (!Permissions::hasPermission($role, 'view-participants')) {
            abort(403, 'You do not have permission to view participants.');
        }
        $participants = EventParticipant::where('eventId', $eventId)->with(['user', 'event'])->get();
        $eventRole = EventRole::class;

        if (! $participant) {
            abort(403, 'You do not have access to this event.');
        }

        return view('events.organizerOverview', compact('participants', 'eventId', 'currentUser', 'eventRole'));
    }

    public function show($id)
    {
        $participant = EventParticipant::findOrFail($id);
        return response()->json($participant);
    }

    public function delete($id)
    {
        $currentUser = auth()->user();
        $participantToDelete = EventParticipant::findOrFail($id);
        $eventId = $participantToDelete->eventId;
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';

        if (!Permissions::hasPermission($role, 'delete-participant')) {
            abort(403, 'You do not have permission to delete participants.');
        }

        if ($participantToDelete->eventRole === EventRole::owner->name) {
            abort(403, 'Owners cannot be deleted.');
        }

        $currentRole = $role;
        $targetRole = $participantToDelete->eventRole;
        if ($currentRole === EventRole::coOwner->name && $targetRole === EventRole::coOwner->name) {
            abort(403, 'Co-Owners cannot delete other Co-Owners.');
        }
        $participantToDelete->delete();
        return response()->json(['message' => 'Participant deleted successfully']);
    }

    public function join(Request $request, $eventId)
    {
        $request->validate([]);
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        EventParticipant::firstOrCreate(
            ['eventId' => $eventId, 'userId' => $userId],
            ['status' => 'accepted']
        );
        return redirect()->back();
    }

    public function decline(Request $request, $eventId)
    {
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        EventParticipant::where('eventId', $eventId)->where('userId', $userId)->delete();
        return redirect()->back();
    }

    public function roleUpdate(Request $request)
    {
        // Owner role cannot be assigned through this endpoint (use transferOwnership)
        $request->validate([
            'eventRole' => 'required|in:coOwner,taskManager,taskWorker,participant',
            'participantId' => 'required|integer',
        ]);

        $participantId = (int) $request->input('participantId');
        $participant = EventParticipant::findOrFail($participantId);

        $currentUser = auth()->user();
        $currentParticipant = EventParticipant::where('eventId', $participant->eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $currentRole = $currentParticipant?->eventRole ?? 'participant';

        // Prevent users from changing their own role via this endpoint
        if ($participant->userId === $currentUser?->id) {
            abort(403, 'You cannot change your own role here.');
        }

        $newRole = $request->input('eventRole');

        // Map the target role to the required permission
        $permissionMap = [
            'coOwner' => 'manage-coOwners',
            'taskManager' => 'manage-taskManagers',
            'taskWorker' => 'manage-taskWorkers',
            'participant' => 'manage-participants',
        ];

        $requiredPermission = $permissionMap[$newRole] ?? null;

        if (!$requiredPermission || !Permissions::hasPermission($currentRole, $requiredPermission)) {
            abort(403, 'You do not have permission to assign this role.');
        }

        // Do not allow changing the owner through this endpoint
        if ($participant->eventRole === EventRole::owner->name) {
            abort(403, 'Cannot change the role of the owner here.');
        }

    $participant->eventRole = $newRole;
    $participant->save();

    return redirect()->back();
    }

    public function rsvp(Request $request, $eventId)
    {
        $request->validate([
            'status' => 'required|in:accepted,declined',
        ]);
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }
        if ($request->input('status') === 'accepted') {
            $event = Event::find($eventId);
            if ($event && $event->participantLimit) {
                $acceptedCount = EventParticipant::where('eventId', $eventId)->where('status', 'accepted')->count();
                if ($acceptedCount >= $event->participantLimit && !EventParticipant::where('eventId', $eventId)->where('userId', $userId)->where('status', 'accepted')->exists()) {
                    return redirect()->back()->with('success', 'Begivenheden er fuld.');
                }
            }
        }
        if ($request->input('status') === 'accepted') {
            EventParticipant::updateOrCreate(
                ['eventId' => $eventId, 'userId' => $userId],
                ['status' => 'accepted']
            );
            return redirect()->back();
        }
        EventParticipant::updateOrCreate(
            ['eventId' => $eventId, 'userId' => $userId],
            ['status' => 'declined']
        );
         return redirect()->back();
    }

    public function transferOwnership(Request $request, $participantId)
    {
        $currentUser = auth()->user();
        $newOwnerParticipant = EventParticipant::findOrFail($participantId);
        $eventId = $newOwnerParticipant->eventId;
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';

        if (!Permissions::hasPermission($role, 'transfer-ownership')) {
            abort(403, 'Du har ikke tilladelse til at overføre ejerskab.');
        }

        if ($newOwnerParticipant->userId === $currentUser->id) {
            abort(403, 'Du er allerede ejer.');
        }

        // Demote current owner to coOwner
        $currentParticipant->eventRole = EventRole::coOwner->name;
        $currentParticipant->save();

        // Promote new owner
        $newOwnerParticipant->eventRole = EventRole::owner->name;
        $newOwnerParticipant->save();

        return redirect()->back()->with('success', 'Ownership has been transferred successfully.');
    }

    public function getParticipantsList($eventId)
    {
        $currentUser = auth()->user();
        $participant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        
        if (!$participant) {
            return response()->json(['error' => 'You do not have access to this event.'], 403);
        }

        $participants = EventParticipant::where('eventId', $eventId)
            ->with(['user'])
            ->get()
            ->map(function($p) {
                return [
                    'id' => $p->id,
                    'name' => $p->user->name ?? 'Ukendt',
                    'email' => $p->user->email ?? null,
                    'status' => $p->status,
                    'eventRole' => $p->eventRole
                ];
            });

        return response()->json($participants);
    }

    public function getVolunteer($eventId)
    {
        $volunteers = EventParticipant::where('eventId', $eventId)
        ->where('eventRole', EventRole::volunteer->name)
        ->get();
        return response()->json($volunteers);
    }

    public function becomeVolunteer(Request $request, $eventId)
    {
        $currentUser = auth()->user();
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }

        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';

        if (!Permissions::hasPermission($role, 'volunteer')) {
            abort(403, 'You do not have permission to volunteer.');
        }
        
        $participant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $userId)
            ->first();
        
        if (!$participant) {
            abort(403, 'You do not have access to this event.');
        }
        
        if ($participant->eventRole === EventRole::volunteer->name) {
            abort(403, 'You are already a volunteer.');
        }
        
        $participant->eventRole = EventRole::volunteer->name;
        $participant->save();
        
        return redirect()->back()->with('success', 'You are now a volunteer.');
    }

    public function cancelVolunteer(Request $request, $eventId)
    {
        $currentUser = auth()->user();
        $userId = Auth::id();
        if (!$userId) {
            return redirect('/signin');
        }

        $currentVolunteer = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $currentVolunteer?->eventRole ?? 'volunteer';

        if (!Permissions::hasPermission($role, 'unvolunteer')) {
            abort(403, 'You do not have permission to cancel volunteering.');
        }

        if (!$currentVolunteer || $currentVolunteer->eventRole !== EventRole::volunteer->name) {
            abort(403, 'You are not currently a volunteer for this event.');
        }

        $currentVolunteer->eventRole = EventRole::participant->name;
        $currentVolunteer->save();

        return redirect()->back()->with('success', 'You are no longer a volunteer.');
    }

    public function removeVolunteer(Request $request, $participantId)
    {
        $currentUser = auth()->user();
        $participantToRemove = EventParticipant::findOrFail($participantId);
        $eventId = $participantToRemove->eventId;
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';

        if (!Permissions::hasPermission($role, 'manage-volunteers')) {
            abort(403, 'You do not have permission to remove volunteers.');
        }

        if ($participantToRemove->eventRole !== EventRole::volunteer->name) {
            abort(403, 'Only volunteers can be removed using this endpoint.');
        }

        $participantToRemove->eventRole = EventRole::participant->name;
        $participantToRemove->save();

        return redirect()->back()->with('success', 'Volunteer removed successfully.');
    }

    public function promoteFromVolunteer(Request $request, $participantId)
    {
        $currentUser = auth()->user();
        $participantToPromote = EventParticipant::findOrFail($participantId);
        $eventId = $participantToPromote->eventId;
        $currentParticipant = EventParticipant::where('eventId', $eventId)
            ->where('userId', $currentUser?->id)
            ->first();
        $role = $currentParticipant?->eventRole ?? 'participant';

        if (!Permissions::hasPermission($role, 'manage-participants')) {
            abort(403, 'You do not have permission to promote participants.');
        }

        if ($participantToPromote->eventRole !== EventRole::volunteer->name) {
            abort(403, 'Only volunteers can be promoted using this endpoint.');
        }

        $participantToPromote->eventRole = EventRole::taskWorker->name;
        $participantToPromote->save();

        return redirect()->back()->with('success', 'Participant promoted from volunteer successfully.');
    }

    public function getVolunteers($eventId)
    {
        $volunteers = EventParticipant::where('eventId', $eventId)
            ->where('eventRole', EventRole::volunteer->name)
            ->with('user')
            ->get()
            ->map(function ($volunteer) {
                return [
                    'id' => $volunteer->id,
                    'name' => $volunteer->user->name ?? 'Ukendt',
                ];
            });

        return view('test', compact('volunteers', 'eventId'));
    }
}
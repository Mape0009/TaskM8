<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Exists;
use App\Models\PinCode;
use App\Models\EventParticipant;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6|confirmed',
            // Optional invitation fields
            'pin' => 'nullable|string|size:4',
            'event_id' => 'nullable|integer',
        ]);
        
        if (User::where('email', $request->input('email'))->exists()) {
            return back()->withErrors(['email' => 'Emailen er allerede i brug.']);
        }

        // Prepare invitation linkage (optional)
        $invitePin = trim((string)$request->input('pin', ''));
        $inviteEventId = $request->input('event_id');
        $inviteEmail = trim(mb_strtolower((string)$request->input('email', '')));
        $validatedPinCode = null;

        if (empty($inviteEventId) && !empty($invitePin)) {
            // If the form didn't carry event_id, derive it from the pin + email
            $pinRowForEvent = PinCode::where('pincode', $invitePin)
                ->whereRaw('LOWER(email) = ?', [$inviteEmail])
                ->orderByDesc('created_at')
                ->first();
            if ($pinRowForEvent && !empty($pinRowForEvent->eventId)) {
                $inviteEventId = $pinRowForEvent->eventId;
                $validatedPinCode = $pinRowForEvent;
            }
        }

        if (!empty($inviteEventId)) {
            // Try strict match first: pin + email + event
            if (!empty($invitePin)) {
                $validatedPinCode = PinCode::where('pincode', $invitePin)
                    ->whereRaw('LOWER(email) = ?', [$inviteEmail])
                    ->where('eventId', $inviteEventId)
                    ->first();
            }

            // Fallback 1: email + event (if user pasted wrong pin but link is valid)
            if (!$validatedPinCode) {
                $validatedPinCode = PinCode::whereRaw('LOWER(email) = ?', [$inviteEmail])
                    ->where('eventId', $inviteEventId)
                    ->first();
            }

            // Fallback 2: pin + event (in case of case variation in email or autofill)
            if (!$validatedPinCode && !empty($invitePin)) {
                $validatedPinCode = PinCode::where('pincode', $invitePin)
                    ->where('eventId', $inviteEventId)
                    ->first();
            }
        }

        // Create a new user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->phonenumber = $request->input('phonenumber', null);
        $user->role = 'user';
        $user->save();

        // If this was an invited signup, attach the user to the event immediately (as pending)
        if ($inviteEventId) {
            EventParticipant::updateOrCreate(
                ['eventId' => $inviteEventId, 'userId' => $user->id],
                ['status' => 'pending']
            );

            // Consume any matching pin codes so they can't be reused
            if ($validatedPinCode) {
                $validatedPinCode->delete();
            } else {
                PinCode::where('eventId', $inviteEventId)
                    ->whereRaw('LOWER(email) = ?', [$inviteEmail])
                    ->delete();
            }
        }

        return redirect('/signin')->with('success', 'Bruger oprettet. Log ind for at begynde.');
    }

    public function show($id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        return response()->json($user, 200);
    }

    public function update(Request $request, $id)
    {
        // Find the user by ID
        $user = User::findOrFail($id);

        // Validate the request data
        $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
            'password' => 'sometimes|required|string|min:8|confirmed',
        ]);

        // Update user details
        if ($request->has('name')) {
            $user->name = $request->input('name');
        }
        if ($request->has('email')) {
            $user->email = $request->input('email');
        }
        if ($request->has('password')) {
            $user->password = bcrypt($request->input('password'));
        }
        $user->save();

        return response()->json(['message' => 'Bruger opdateret'], 200);
    }

    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required|string',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        $user = auth()->user();

        // Check if current password matches
        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Den nuværende adgangskode er ikke korrekt.']);
        }

        // Update password
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Adgangskode er blevet ændret!');
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Models\PinCode;
use App\Models\EventParticipant;

class UserController extends Controller
{
    public function createUser(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'pin' => 'nullable|string|size:4',
            'event_id' => 'nullable|integer',
        ]);

        $pinRecord = null;
        // If pin is present, validate it
        if ($request->filled('pin')) {
            $pin = $request->input('pin');
            $email = $request->input('email');

            $pinRecord = PinCode::where('email', $email)
                ->where('pincode', $pin)
                ->where('createdAt', '>=', now()->subDays(7))
                ->first();

            if (!$pinRecord) {
                return back()->withErrors(['pin' => 'Ugyldig eller udløbet PIN-kode.'])->withInput();
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

        // If a valid pin was used, create a pending event participation so the event is visible, then delete the pin
        if ($pinRecord) {
            $eventId = $pinRecord->eventId ?: $request->input('event_id');
            if (!empty($eventId)) {
                EventParticipant::firstOrCreate(
                    ['eventId' => $eventId, 'userId' => $user->id],
                    ['status' => 'pending']
                );
            }
            $pinRecord->delete();
        }

        return redirect('/signin')->with('success', 'Bruger oprettet. Log ind for at begynde.');
    }

    public function createAdmin(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($request->input('token') == self::adminToken) {
            $user = new User();
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->password = bcrypt($request->input('password'));
            $user->role = 'admin';
            $user->token = $request->input('token');
            $user->save();

            return response()->json(['message' => 'Admin oprettet'], 201);
        } else {
            return response()->json(['message' => 'Ugyldig admin token'], 403);
        }
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
            'new_password' => 'required|string|min:8|confirmed',
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

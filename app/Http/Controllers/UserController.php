<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    private const adminToken = 'Happy Pride';
    public function createUser(Request $request)
    {
        // Validate the request data
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create a new user
        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = bcrypt($request->input('password'));
        $user->role = 'user';
        $user->save();

        return response()->json(['message' => 'User created successfully'], 201);
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

            return response()->json(['message' => 'Admin created successfully'], 201);
        } else {
            return response()->json(['message' => 'Invalid admin token'], 403);
        }
    }

    public function login(Request $request)
    {
        $email = $request->input('email');
        $password = $request->input('password');

        $user = User::where('email', $email)->first();
        if ($user && Hash::check($password, $user->password)) 
        {
            $user->loggedIn = true;
            $user->save();
            return response()->json(['message' => 'Login successful', 'user' => $user], 200);
        }

        return response()->json(['message' => 'Invalid credentials'], 401);
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

        return response()->json(['message' => 'User updated successfully'], 200);
    }
}

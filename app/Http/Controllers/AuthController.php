<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Firebase\JWT\JWT;

class AuthController extends Controller
{
    public function login(LoginRequest $request)
    {
        $credentials = $request->validated();

        if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
            return response()->json(['message' => 'Invalid credentials'], Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $user = Auth::user();

        $decryptedToken = $user->createToken($user->id)->plainTextToken;
        $encryptedToken = Crypt::encrypt($decryptedToken);

        $jwt = JWT::encode([$encryptedToken], config('app.key'), 'HS256');

        $cookie = cookie('jwt', $jwt);

        return response([$jwt])->withCookie($cookie);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\LoginRequest;
use Symfony\Component\HttpFoundation\Response;
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

        return redirect('/')->withCookie($cookie);
    }

    public function logout(Request $request)
    {
        try {
            $jwt = $request->cookie('jwt');

            if ($jwt) {
                $decoded = JWT::decode($jwt, new \Firebase\JWT\Key(config('app.key'), 'HS256'));
                $encryptedToken = $decoded->{0};
                $decryptedToken = \Crypt::decrypt($encryptedToken);

                // Simulate request with Bearer token to resolve current token user
                $request->headers->set('Authorization', 'Bearer ' . $decryptedToken);

                $user = $request->user();

                if ($user && $user->currentAccessToken()) {
                    $user->currentAccessToken()->delete(); // Revoke token
                }
            }
        } catch (\Exception $e) {
            // Optionally log error or ignore
        }

        // Log out of Laravel session as well
        \Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        $cookie = \Cookie::forget('jwt');

        return redirect('/')->withCookie($cookie);
    }
}

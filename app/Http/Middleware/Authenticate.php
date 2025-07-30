<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Firebase\JWT\Key;
use Illuminate\Support\Facades\Crypt;
use Firebase\JWT\JWT;

class Authenticate extends Middleware
{

    protected function redirectTo(Request $request): ?string
    {
        // Always redirect to login for web requests (Blade/forms)
        if (!$request->expectsJson()) {
            return '/signin';
        }
        // For API requests, return null so Laravel returns a 401 JSON response
        return null;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle($request, Closure $next, ...$guards): Response
    {
        dd('at authenticate middleware');
        // Check for JWT in cookie or Authorization header
        $jwtCookie = $request->cookie('jwt');
        $jwtHeader = null;
        $authHeader = $request->header('Authorization');
        if ($authHeader && preg_match('/Bearer\s(.+)/', $authHeader, $matches)) {
            $jwtHeader = $matches[1];
        }

        $jwt = $jwtCookie ?: $jwtHeader;
        if (!$jwt) {
            // No JWT found, redirect to signin
            return redirect('/signin');
        }

        try {
            $jwtDecoded = JWT::decode($jwt, new Key(config('app.key'), 'HS256'));
            // $jwtDecoded is a stdClass with numeric property '0' (since encoded as array)
            $encryptedToken = $jwtDecoded->{0};
            $decryptedToken = Crypt::decrypt($encryptedToken);
            $request->headers->set('Authorization', 'Bearer ' . $decryptedToken);
        } catch (\Exception $e) {
            // If JWT is invalid, remove the cookie so user is forced to login again
            \Cookie::queue(\Cookie::forget('jwt'));
            return redirect('/signin');
        }

        $this->authenticate($request, $guards);

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next)
    {
        // Exclude specific routes from JWT authentication
        if ($request->is('users') || $request->is('login')) {
            return $next($request);
        }

        try {
            // Parse token from the request
            $user = JWTAuth::parseToken()->authenticate();
        } catch (JWTException $e) {
            // Return error response if JWT is invalid or missing
            return response()->json(['error' => 'Token not provided or invalid'], 401);
        }

        return $next($request);
    }
}

<?php

namespace App\Http\Middleware;

use App\Http\Controllers\Api\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Tymon\JWTAuth\Facades\JWTAuth;

class JwtMiddleware
{
    use ApiResponse;

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        try {
            // Attempt to parse the token from the request
            $token = JWTAuth::parseToken();

            // Check if the token is valid
            if (!$token->check()) {
                /*                return response()->json(['error' => 'Invalid token'], 401);*/
                return $this->responseApiError('Invalid token', 401);
            }
        } catch (\Exception $e) {
            /*            return response()->json(['error' => 'Token not found'], 401);*/
            return $this->responseApiError('Token not found', 401);

        }

        return $next($request);
    }
}

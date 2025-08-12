<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RefreshTokenMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!$request->cookie('refresh_token')) {
            return response()->json(
                ['message' => 'Refresh token not found'], 
                Response::HTTP_UNAUTHORIZED
            );
        }

        return $next($request);
    }
}

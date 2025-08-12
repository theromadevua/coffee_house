<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CorsMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);
    
        $origin = $request->headers->get('Origin');
    
        if ($origin) {

            $response->headers->set('Access-Control-Allow-Origin', $origin);
            $response->headers->set('Access-Control-Allow-Methods', 'GET, POST, PUT, PATCH, DELETE, OPTIONS');
            $response->headers->set('Access-Control-Allow-Headers', 'Origin, Content-Type, Authorization, application/json, X-Requested-With, multipart/form-data');
            $response->headers->set('Access-Control-Allow-Credentials', 'true'); 
        }
    
        return $response;
    }
    
}

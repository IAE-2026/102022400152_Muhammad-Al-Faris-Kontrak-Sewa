<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IaeKeyMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $apiKey = $request->header('X-IAE-KEY');

        if ($apiKey !== '102022400152') {
            return response()->json([
                'status' => 'error',
                'message' => 'Unauthorized. X-IAE-KEY is missing or invalid.',
                'errors' => null,
            ], 401);
        }

        return $next($request);
    }
}
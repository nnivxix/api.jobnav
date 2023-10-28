<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class UnAuthenticate
{
    public function handle(Request $request, Closure $next)
    {
        if (auth('sanctum')->check()) {
            return response()->json(['errors' => 'You have logged in.'], 403);
        }
        return $next($request);
    }
}

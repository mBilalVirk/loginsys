<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UpdateLastSeen
{
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::check()) {
            Auth::user()->update(['last_seen' => now()]);
            // Or more performant: only update if older than e.g. 1 minute
            // if (Auth::user()->last_seen?->lt(now()->subMinute())) { ... }
        }

        return $next($request);
    }
}
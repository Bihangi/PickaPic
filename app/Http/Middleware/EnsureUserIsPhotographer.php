<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EnsureUserIsPhotographer
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->role === 'photographer') {
            return $next($request);
        }

        // Redirect unauthorized users
        return redirect()->route('photographer.login')->withErrors([
            'email' => 'Access denied. Only photographers can access that page.'
        ]);
    }
}

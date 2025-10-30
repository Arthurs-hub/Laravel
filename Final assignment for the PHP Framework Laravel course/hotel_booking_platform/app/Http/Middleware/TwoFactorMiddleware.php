<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TwoFactorMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = Auth::user();

        if ($user && $user->two_factor_enabled && !$request->session()->get('two_factor_verified')) {
            if (!$request->routeIs('two-factor.*') && !$request->routeIs('logout')) {
                return redirect()->route('two-factor.verify')->with('info', 'Please complete the two-factor authentication.');
            }
        }

        return $next($request);
    }
}
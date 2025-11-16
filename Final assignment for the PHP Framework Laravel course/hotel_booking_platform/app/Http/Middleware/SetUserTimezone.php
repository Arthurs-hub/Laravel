<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class SetUserTimezone
{
    public function handle(Request $request, Closure $next)
    {
        $userTimezone = null;

        if (Auth::check() && Auth::user()->timezone) {
            $userTimezone = Auth::user()->timezone;
        }
       
        elseif ($request->cookie('timezone')) {
            $userTimezone = $request->cookie('timezone');
        }

        if ($userTimezone) {
            Session::put('user_timezone', $userTimezone);
        }

        return $next($request);
    }
}

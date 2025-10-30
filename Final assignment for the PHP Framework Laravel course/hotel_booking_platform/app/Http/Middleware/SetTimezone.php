<?php

namespace App\Http\Middleware;

use Closure;

class SetTimezone
{
    public function handle($request, Closure $next)
    {
        if ($timezone = $request->cookie('timezone')) {
            config(['app.timezone' => $timezone]);
            date_default_timezone_set($timezone);
        }

        return $next($request);
    }
}
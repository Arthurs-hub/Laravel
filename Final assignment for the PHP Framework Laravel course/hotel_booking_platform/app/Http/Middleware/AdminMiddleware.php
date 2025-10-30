<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            return redirect('/login');
        }
        
        $user = auth()->user();
        if ($user->role !== 'admin' && $user->role !== 'manager') {
            return redirect('/');
        }

        return $next($request);
    }
}
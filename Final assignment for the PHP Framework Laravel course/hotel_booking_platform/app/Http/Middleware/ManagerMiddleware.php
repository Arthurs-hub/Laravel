<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ManagerMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json(['message' => 'Unauthenticated'], 401);
            }
            return redirect('/login');
        }
        
        $user = auth()->user();
        if (!$user->isAdmin() && !$user->isManager()) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Forbidden'], 403);
            }
            return redirect('/')->with('error', 'Доступ запрещен');
        }
        
        // For manager routes, check if user has assigned hotel (applies to both admin and manager)
        if ($user->isManager() || $user->isAdmin()) {
            $hotel = \App\Models\Hotel::where('manager_id', $user->id)->first();
            if (!$hotel) {
                if ($request->expectsJson()) {
                    return response()->json(['message' => 'No hotels available for management'], 403);
                }
                return redirect('/')->with('error', 'No hotels available for management. Contact administrator.');
            }
        }

        return $next($request);
    }
}
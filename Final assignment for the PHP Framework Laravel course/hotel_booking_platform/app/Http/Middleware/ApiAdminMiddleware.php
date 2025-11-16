<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAdminMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (!auth()->check()) {
            \Log::info('API Admin Middleware: User not authenticated', [
                'session_id' => $request->session()->getId(),
                'has_session' => $request->hasSession(),
                'session_data' => $request->session()->all()
            ]);
            return response()->json(['message' => 'Unauthenticated'], 401);
        }
        
        $user = auth()->user();
        \Log::info('API Admin Middleware: User authenticated', ['user_id' => $user->id, 'role' => $user->role, '2fa_enabled' => $user->two_factor_enabled, '2fa_verified' => session('two_factor_verified')]);
        
        // Check 2FA verification if enabled
        if ($user->two_factor_enabled && !session('two_factor_verified')) {
            \Log::info('API Admin Middleware: 2FA required but not verified');
            return response()->json(['message' => 'Two-factor authentication required'], 401);
        }
        
        if (!$user || ($user->role !== 'admin' && $user->role !== 'manager')) {
            \Log::info('API Admin Middleware: User forbidden', ['role' => $user->role ?? 'null']);
            return response()->json(['message' => 'Forbidden'], 403);
        }

        return $next($request);
    }
}
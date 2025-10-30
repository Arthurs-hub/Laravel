<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;

class AuthController extends Controller
{
    /**
     * Register a new user (API endpoint for testing)
     * Only available in local development environment
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'full_name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'phone' => ['required', 'string', 'max:20'],
            'date_of_birth' => ['required', 'date'],
            'gender' => ['required', 'in:male,female,other'],
            'country' => ['required', 'string', 'max:100'],
            'city' => ['required', 'string', 'max:100'],
            'address' => ['required', 'string', 'max:255'],
            'postal_code' => ['required', 'string', 'max:20'],
            'passport_number' => ['required', 'string', 'max:50'],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'two_factor_enabled' => ['boolean'],
        ]);


        $user = User::create([
            'full_name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'date_of_birth' => $validated['date_of_birth'],
            'gender' => $validated['gender'],
            'country' => $validated['country'],
            'city' => $validated['city'],
            'address' => $validated['address'],
            'postal_code' => $validated['postal_code'],
            'passport_number' => $validated['passport_number'],
            'password' => Hash::make($validated['password']),
            'two_factor_enabled' => $request->boolean('two_factor_enabled'),
        ]);

        event(new Registered($user));

        try {
            $currentLocale = app()->getLocale();
            $userLocale = session('locale', config('app.locale'));
            app()->setLocale($userLocale);

            Mail::send('welcome-email', ['user' => $user], function ($message) use ($user) {
                $message->to($user->email)->subject(__('email.welcome.subject', ['app_name' => config('app.name')]));
            });

            app()->setLocale($currentLocale);
        } catch (\Exception $e) {
            \Log::error('Failed to send welcome email: ' . $e->getMessage());
        }

        Auth::login($user);

        return response()->json([
            'success' => true,
            'message' => 'Registration successful',
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'redirect' => $user->two_factor_enabled ? '/two-factor/method' : '/dashboard'
        ], 201);
    }

    /**
     * Login user (API endpoint for testing)
     * Only available in local development environment
     */
    public function login(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials',
                'errors' => [
                    'email' => ['The provided credentials are incorrect.']
                ]
            ], 401);
        }

        Auth::login($user, $request->boolean('remember'));

        if ($user->two_factor_enabled) {
            if ($user->two_factor_method === 'email') {
                $code = $user->generateEmailCode();
                $user->two_factor_code = $code;
                $user->two_factor_expires_at = now()->addMinutes(10);
                $user->save();

                try {
                    Mail::send('two-factor-code', ['user' => $user, 'code' => $code], function ($message) use ($user) {
                        $message->to($user->email)->subject(__('email.2fa_code.subject', ['app_name' => config('app.name')]));
                    });
                } catch (\Exception $e) {
                    \Log::error('Failed to send 2FA code: ' . $e->getMessage());
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'Two-factor authentication required',
                'two_factor_required' => true,
                'redirect' => '/two-factor/verify'
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => [
                'id' => $user->id,
                'full_name' => $user->full_name,
                'email' => $user->email,
                'role' => $user->role,
            ],
            'redirect' => '/hotels'
        ]);
    }

    /**
     * Logout user
     */
    public function logout(Request $request): JsonResponse
    {
        Auth::logout();
        
        if ($request->hasSession()) {
            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully'
        ]);
    }
}

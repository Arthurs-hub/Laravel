<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

class TwoFactorController extends Controller
{
    public function challenge()
    {
        $user = Auth::user();
        if (!$user || !$user->two_factor_enabled) {
            return redirect()->route('login');
        }

        return view('auth.two-factor-challenge');
    }

    public function verify(Request $request)
    {
        $request->validate([
            'code' => 'required|string|size:6',
            'type' => 'nullable|string'
        ]);

        $user = Auth::user();
        if (!$user) {
            return redirect()->route('login');
        }

        $method = $request->input('type') ?? $user->two_factor_method ?? 'email';

        if ($method === 'google_authenticator') {
            $method = 'app';
        }

        $code = (string) $request->input('code');
        $verified = false;

        if ($method === 'app') {
            
            if (method_exists($user, 'verifyAppCode') && $user->verifyAppCode($code)) {
                $verified = true;
            }
        } else {
            
            $expiresAt = $user->two_factor_expires_at ? Carbon::parse($user->two_factor_expires_at) : null;

            if (
                !empty($user->two_factor_code)
                && hash_equals((string) $user->two_factor_code, $code)
                && $expiresAt !== null
                && $expiresAt->isFuture()
            ) {
                $verified = true;
            }
        }

        if (!$verified) {
            return back()->withErrors(['code' => __('auth.invalid_two_factor_code')]);
        }

        $user->email_verified_at = $user->email_verified_at ?? now();
        $user->two_factor_code = null;
        $user->two_factor_expires_at = null;
        $user->save();

        session(['two_factor_verified' => true]);

        return redirect()->intended(route('dashboard'));
    }

    public function sendEmailCode()
    {
        $user = Auth::user();
        if (!$user) {
            return response()->json(['success' => false], 401);
        }

        $code = $user->generateEmailCode();

        $user->two_factor_code = $code;
        $user->two_factor_expires_at = now()->addMinutes(10);
        $user->save();

        try {
            Mail::send('two-factor-code', ['user' => $user, 'code' => $code], function ($message) use ($user) {
                $message->to($user->email)->subject(__('email.2fa_code.subject', ['app_name' => config('app.name')]));
            });
        } catch (\Exception $e) {
            
        }

        return response()->json(['success' => true]);
    }

    public function setup()
    {
        $user = Auth::user();

        if (!$user->two_factor_secret) {
            $user->two_factor_secret = $user->generateTwoFactorSecret();
            $user->save();
        }

        return view('profile.two-factor-setup', compact('user'));
    }

    public function enable(Request $request)
    {
        $request->validate(['code' => 'required|string|size:6']);

        $user = Auth::user();

        if (!method_exists($user, 'verifyAppCode') || !$user->verifyAppCode($request->code)) {
            return back()->withErrors(['code' => __('auth.invalid_two_factor_code')]);
        }

        $user->update([
            'two_factor_enabled' => true,
            'two_factor_confirmed_at' => now(),
            'two_factor_recovery_codes' => $this->generateRecoveryCodes(),
        ]);

        return redirect()->route('profile.edit')->with('success', __('twofactor.enabled'));
    }

    public function disable(Request $request)
    {
        $request->validate(['password' => 'required|current_password']);

        Auth::user()->update([
            'two_factor_enabled' => false,
            'two_factor_secret' => null,
            'two_factor_recovery_codes' => null,
            'two_factor_confirmed_at' => null
        ]);

        return redirect()->route('profile.edit')->with('success', __('twofactor.disabled'));
    }

    private function generateRecoveryCodes(): array
    {
        $codes = [];
        for ($i = 0; $i < 8; $i++) {
            $codes[] = strtoupper(substr(md5(random_bytes(16)), 0, 8));
        }
        return $codes;
    }
}
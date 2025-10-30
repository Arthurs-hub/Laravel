<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;


class TwoFactorController extends Controller
{
    public function showMethodSelection()
    {
        return view('auth.two-factor-method');
    }

    public function selectMethod(Request $request)
    {
        $request->validate([
            'method' => 'required|in:email,google_authenticator'
        ]);

        $user = Auth::user();
        $user->two_factor_method = $request->input('method');

        if ($request->input('method') === 'email') {
            $code = $user->getEmailCode();

            \DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'two_factor_code' => $code,
                    'two_factor_expires_at' => now()->addMinutes(10),
                    'two_factor_method' => $request->input('method'),
                    'updated_at' => now()
                ]);

            $user->refresh();

            \Log::info('2FA Code Generated', [
                'user_id' => $user->id,
                'code' => $code,
                'saved_code' => $user->two_factor_code,
                'expires_at' => $user->two_factor_expires_at,
                'method' => $user->two_factor_method
            ]);

            $currentLocale = app()->getLocale();
            $userLocale = session('locale', config('app.locale'));
            app()->setLocale($userLocale);

            Mail::send('two-factor-code', ['user' => $user, 'code' => $code], function ($message) use ($user) {
                $message->to($user->email)->subject(__('email.2fa_code.subject', ['app_name' => config('app.name')]));
            });

            app()->setLocale($currentLocale);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Email 2FA method selected',
                    'redirect' => '/api/two-factor/verify'
                ]);
            }
        } else {
            $user->two_factor_secret = $user->getTwoFactorSecret();
            $user->save();
            
            if ($request->expectsJson()) {
                $appName = config('app.name');
                $secret = $user->two_factor_secret;
                
                return response()->json([
                    'success' => true,
                    'message' => 'Google Authenticator setup required',
                    'setup_data' => [
                        'manual_entry' => [
                            'account_name' => $user->email,
                            'secret_key' => $secret,
                            'issuer' => $appName
                        ],
                        'instructions' => [
                            'en' => 'Configure Google Authenticator with the data below',
                            'ru' => 'Настройте Google Authenticator с данными ниже',
                            'de' => 'Konfigurieren Sie Google Authenticator mit den Daten'
                        ]
                    ],
                    'redirect' => '/api/two-factor/verify'
                ]);
            }
        }

        $user->save();

        return redirect()->route('two-factor.verify');
    }

    public function showVerification()
    {
        $user = Auth::user();
        $qrCode = null;

        if ($user->two_factor_method === 'google_authenticator' && $user->two_factor_secret) {
            $appName = config('app.name');
            $secret = $user->two_factor_secret;
            $qrCode = "otpauth://totp/{$appName}:{$user->email}?secret={$secret}&issuer={$appName}";
        }

        return view('auth.two-factor-verify', compact('qrCode'));
    }

    public function verify(Request $request)
    {
        $sessionKey = 'verifying_2fa_' . Auth::id();
        if (session($sessionKey)) {
            return back()->withErrors(['code' => __('flash.2fa_code_in_progress')]);
        }
        session([$sessionKey => true]);

        $request->validate([
            'code' => 'required|string|size:6'
        ]);

        $user = Auth::user();
        $isValid = false;

        if ($user->two_factor_method === 'email') {
            $userData = \DB::table('users')->where('id', $user->id)->first();

            \Log::info('2FA Code Verification', [
                'user_id' => $user->id,
                'input_code' => $request->code,
                'stored_code' => $userData->two_factor_code,
                'expires_at' => $userData->two_factor_expires_at,
                'is_expired' => $userData->two_factor_expires_at ? $userData->two_factor_expires_at <= now() : 'no_expiry'
            ]);

            $isValid = $userData->two_factor_code &&
                (string) $userData->two_factor_code === (string) $request->code &&
                $userData->two_factor_expires_at &&
                \Carbon\Carbon::parse($userData->two_factor_expires_at)->gt(now());
        } else {
            $isValid = $user->verifyAppCode($request->code);
        }

        if ($isValid) {
            $request->session()->put('two_factor_verified', true);

            \DB::table('users')
                ->where('id', $user->id)
                ->update([
                    'two_factor_code' => null,
                    'two_factor_expires_at' => null,
                    'email_verified_at' => now(),
                    'updated_at' => now()
                ]);

            session()->forget($sessionKey);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Two-factor authentication completed',
                    'user' => [
                        'id' => $user->id,
                        'full_name' => $user->full_name,
                        'email' => $user->email,
                        'role' => $user->role
                    ],
                    'redirect' => '/api/hotels'
                ]);
            }
            
            return redirect()->intended(route('dashboard'));
        }

        session()->forget($sessionKey);

        $userData = \DB::table('users')->where('id', $user->id)->first();
        $errorMsg = $user->two_factor_method === 'email' ?
            (
                !$userData->two_factor_code ? __('flash.2fa_code_not_found') :
                ($userData->two_factor_expires_at && \Carbon\Carbon::parse($userData->two_factor_expires_at)->lte(now()) ? __('flash.2fa_code_expired') : __('flash.2fa_code_invalid'))
            ) : __('flash.2fa_code_invalid');

        if ($request->expectsJson()) {
            return response()->json([
                'success' => false,
                'message' => $errorMsg,
                'errors' => ['code' => [$errorMsg]]
            ], 422);
        }

        return back()->withErrors(['code' => $errorMsg]);
    }

    public function resendCode()
    {
        $user = Auth::user();

        if ($user->two_factor_method !== 'email') {
            return response()->json(['success' => false, 'message' => __('flash.2fa_resend_only_email')]);
        }

        $code = $user->getEmailCode();

        \DB::table('users')
            ->where('id', $user->id)
            ->update([
                'two_factor_code' => $code,
                'two_factor_expires_at' => now()->addMinutes(10),
                'updated_at' => now()
            ]);

        $user->refresh();

        $currentLocale = app()->getLocale();
        $userLocale = session('locale', config('app.locale'));
        app()->setLocale($userLocale);

        Mail::send('two-factor-code', ['user' => $user, 'code' => $code], function ($message) use ($user) {
            $message->to($user->email)->subject(__('email.2fa_code.subject', ['app_name' => config('app.name')]));
        });

        app()->setLocale($currentLocale);

        return response()->json(['success' => true, 'message' => __('flash.verification_code_sent')]);
    }
}
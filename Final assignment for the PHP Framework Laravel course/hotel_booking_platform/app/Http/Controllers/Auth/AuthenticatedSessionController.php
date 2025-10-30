<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    public function create(): View
    {
        return view('auth.login');
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        if ($request->user()->two_factor_method) {
            $user = $request->user();

            if ($user->two_factor_method === 'email') {
                $code = $user->generateEmailCode();
                $user->two_factor_code = $code;
                $user->two_factor_expires_at = now()->addMinutes(10);
                $user->save();

                $currentLocale = app()->getLocale();
                $userLocale = session('locale', config('app.locale'));
                app()->setLocale($userLocale);

                Mail::send('two-factor-code', ['user' => $user, 'code' => $code], function ($message) use ($user) {
                    $message->to($user->email)->subject(__('email.2fa_code.subject', ['app_name' => config('app.name')]));
                });

                app()->setLocale($currentLocale);
            }

            return redirect()->route('two-factor.verify');
        }

        return redirect()->intended(route('dashboard', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
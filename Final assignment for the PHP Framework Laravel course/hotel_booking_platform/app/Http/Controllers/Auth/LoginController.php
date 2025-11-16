<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return back()->withErrors(['email' => 'Неверные данные для входа']);
        }

        Auth::login($user, $request->boolean('remember'));

        if ($user->two_factor_enabled && !$user->email_verified) {
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

        return redirect()->intended(route('hotels.index'));
    }


    public function store(Request $request)
    {
        return $this->login($request);
    }

    
    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

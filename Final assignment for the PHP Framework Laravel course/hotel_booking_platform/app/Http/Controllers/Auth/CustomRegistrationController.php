<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rules;
use Illuminate\View\View;

class CustomRegistrationController extends Controller
{
    public function create(): View
    {
        return view('auth.register');
    }

    public function store(Request $request): RedirectResponse
    {
        $request->validate([
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
            'full_name' => $request->full_name,
            'email' => $request->email,
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'passport_number' => $request->passport_number,
            'password' => Hash::make($request->password),
            'two_factor_enabled' => $request->boolean('two_factor_enabled'),
        ]);

        event(new Registered($user));

        $currentLocale = app()->getLocale();
        $userLocale = session('locale', config('app.locale'));
        app()->setLocale($userLocale);

        Mail::send('welcome-email', ['user' => $user], function ($message) use ($user) {
            $message->to($user->email)->subject(__('email.welcome.subject', ['app_name' => config('app.name')]));
        });

        app()->setLocale($currentLocale);

        Auth::login($user);

        if ($user->two_factor_enabled) {
            return redirect()->route('two-factor.method');
        }

        return redirect()->route('dashboard');
    }
}
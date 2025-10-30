<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class RegisterController extends Controller
{
    public function showRegistrationForm()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'phone' => 'required|string|max:20',
            'date_of_birth' => 'required|date',
            'gender' => 'required|in:male,female,other',
            'country' => 'required|string|max:100',
            'city' => 'required|string|max:100',
            'address' => 'required|string|max:255',
            'postal_code' => 'required|string|max:20',
            'passport_number' => 'required|string|max:50',
            'two_factor_enabled' => 'boolean'
        ]);

        $nameParts = explode(' ', $request->full_name, 2);
        
        $user = User::create([
            'full_name' => $request->full_name,
            'first_name' => $nameParts[0],
            'last_name' => $nameParts[1] ?? '',
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'date_of_birth' => $request->date_of_birth,
            'gender' => $request->gender,
            'country' => $request->country,
            'city' => $request->city,
            'address' => $request->address,
            'postal_code' => $request->postal_code,
            'passport_number' => $request->passport_number,
            'two_factor_enabled' => $request->boolean('two_factor_enabled'),
            'role' => 'user'
        ]);

        Auth::login($user);

        if ($user->two_factor_enabled) {
            return redirect()->route('two-factor.method');
        }

        Auth::logout();
        return redirect()->route('login')->with('success', 'Регистрация завершена. Войдите в систему.');
    }
}

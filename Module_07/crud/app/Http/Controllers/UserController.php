<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json($users);
    }

    public function get($id)
    {
        $user = User::find($id);
        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }
        return response()->json($user);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:50',
            'surname' => 'required|string|max:50',
            'email' => [
                'required',
                'email',
                'max:255',
                'regex:/^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,}$/i',
                Rule::unique('users', 'email'),
            ],
        ]);

        $existingUser = User::where('email', $validated['email'])->first();
        if ($existingUser) {
            return redirect()->back()
                ->withInput()
                ->with('error', 'A user with this email already exists.');
        }

        $user = User::create($validated);

        return redirect()->back()
            ->with('success', 'User created successfully!');
    }
}

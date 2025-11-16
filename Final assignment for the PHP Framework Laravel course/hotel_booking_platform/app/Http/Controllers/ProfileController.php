<?php

namespace App\Http\Controllers;

use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Schema;
use Illuminate\View\View;

class ProfileController extends Controller
{

    public function edit(Request $request): View
    {
        $user = $request->user();
        $user->load('bookings.room.hotel', 'reviews.reviewable');
        return view('profile.edit', compact('user'));
    }

    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $request->user()->id,
            'phone' => 'required|string|max:20',
            'country' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'address' => 'nullable|string|max:255',
            'timezone' => 'nullable|string|max:50',
            'language' => 'nullable|string|max:5'
        ]);

        $user = $request->user();

        $originalEmail = $user->email;

        $user->fill($validated);

        if ($originalEmail !== $user->email) {
            $user->email_verified_at = null;
        }

        if ($request->filled('cropped_avatar')) {

            $imageData = $request->input('cropped_avatar');
            $imageData = str_replace('data:image/jpeg;base64,', '', $imageData);
            $imageData = str_replace(' ', '+', $imageData);
            $imageData = base64_decode($imageData);

            $filename = 'avatar_' . time() . '_' . $user->id . '.jpg';
            $path = 'avatars/' . $filename;

            \Storage::disk('public')->put($path, $imageData);

            if (\Schema::hasColumn('users', 'avatar')) {
                $user->avatar = $path;
            }
        } elseif ($request->hasFile('avatar')) {
            $path = $request->file('avatar')->store('avatars', 'public');
            if (\Schema::hasColumn('users', 'avatar')) {
                $user->avatar = $path;
            }
        }

        $user->save();
        return redirect()->route('profile.edit')->with('success', __('profile.profile_updated'));
    }

    public function toggle2FA(Request $request)
    {
        $user = $request->user();
        $user->two_factor_enabled = !$user->two_factor_enabled;
        $user->save();

        $status = $user->two_factor_enabled ? __('profile.2fa_enabled') : __('profile.2fa_disabled');
        return redirect()->route('profile.edit')->with('success', __('profile.2fa_status_changed', ['status' => $status]));
    }

    public function cancelBooking(Request $request, $booking)
    {
        $bookingModel = \App\Models\Booking::findOrFail($booking);

        if ($request->user()->id !== $bookingModel->user_id) {
            abort(403);
        }
        
        $bookingModel->update(['status' => 'cancelled']);
        return redirect()->route('profile.edit')->with('success', __('profile.booking_cancelled'));
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

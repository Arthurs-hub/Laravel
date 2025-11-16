<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;

class ManagerDashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user->isAdmin() && !$user->isManager()) {
            abort(403, __('Access denied'));
        }

        $hotel = $user->managedHotel;

        if (!$user->isAdmin() && !$hotel) {
            abort(403, __('You are not assigned as a hotel manager'));
        }

        if ($user->isAdmin() && !$hotel) {
            $hotel = \App\Models\Hotel::first() ?? \App\Models\Hotel::factory()->create();
        }

        if ($user->isManager() && !$hotel) {
            $firstHotel = \App\Models\Hotel::whereNull('manager_id')->first();
            if ($firstHotel) {
                $firstHotel->update(['manager_id' => $user->id]);
                $hotel = $firstHotel;
            } else {
                abort(403, __('No hotels available for management. Contact administrator.'));
            }
        }

        $stats = [
            'total_rooms' => $hotel->rooms()->count(),
            'total_bookings' => $hotel->rooms()->withCount('bookings')->get()->sum('bookings_count'),
            'active_bookings' => $hotel->rooms()->whereHas('bookings', function ($q) {
                $q->where('started_at', '<=', now())->where('finished_at', '>=', now());
            })->count(),
            'total_reviews' => $hotel->reviews()->count(),
            'average_rating' => $hotel->reviews()->avg('rating') ?? 0,
        ];

        return view('manager-dashboard', compact('hotel', 'stats'));
    }
}
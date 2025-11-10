<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Booking;
use App\Models\User;
use App\Models\Review;
use App\Models\Facility;


class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'hotels_count' => Hotel::count(),
            'rooms_count' => Room::count(),
            'bookings_count' => Booking::count(),
            'users_count' => User::where('role', 'user')->count(),
            'reviews_count' => Review::count(),
            'facilities_count' => Facility::count(),
            'total_revenue' => Booking::sum('price'),
        ];

        $recent_bookings = Booking::with(['user', 'room.hotel'])
            ->latest()
            ->take(5)
            ->get();

        return view('admin-dashboard', compact('stats', 'recent_bookings'));
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;


class BookingController extends Controller
{
    public function index()
    {
        $bookings = Booking::with(['user', 'room.hotel'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        $booking->load(['user', 'room.hotel']);
        return view('admin.bookings.show', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,cancelled'
        ]);

        $booking->update([
            'status' => $request->status
        ]);

        return redirect()->route('admin.bookings.index')
            ->with('success', __('admin.booking_updated_successfully'));
    }

    public function approve(Booking $booking)
    {
        $booking->update(['status' => 'confirmed']);
        
        return redirect()->route('admin.bookings.index')
            ->with('success', __('admin.booking_approved_successfully'));
    }

    public function destroy(Booking $booking)
    {
        $booking->delete();
        return redirect()->route('admin.bookings.index')
            ->with('success', __('admin.booking_deleted_successfully'));
    }
}
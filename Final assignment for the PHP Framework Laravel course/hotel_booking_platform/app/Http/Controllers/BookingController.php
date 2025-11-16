<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Http\Requests\StoreBookingRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;


class BookingController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $bookings = Booking::where('user_id', Auth::id())
            ->with('room.hotel')
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }

    public function create(Request $request)
    {
        $roomId = $request->get('room_id') ?? $request->get('room');
        $room = Room::with('hotel')->findOrFail($roomId);

        return view('bookings.create', compact('room'));
    }

    public function store(StoreBookingRequest $request)
    {
        $validated = $request->validated();

        $room = Room::findOrFail($validated['room_id']);
        $startDate = Carbon::parse($validated['started_at']);
        $finishDate = Carbon::parse($validated['finished_at']);

        $isBooked = Booking::where('room_id', $room->id)
            ->where(function ($query) use ($startDate, $finishDate) {
                $query->where(function ($q) use ($startDate, $finishDate) {
                    $q->where('started_at', '<', $finishDate)
                        ->where('finished_at', '>', $startDate);
                });
            })->exists();

        if ($isBooked) {
            return redirect()->route('bookings.index')->withErrors(['booking_error' => __('booking.dates_already_taken')])->withInput();
        }

        $days = $startDate->diffInDays($finishDate);

        $adults = $validated['adults'] ?? 1;
        $children = $validated['children'] ?? 0;

        $pricePerNight = $room->price;
        if ($adults > 2) {
            $pricePerNight += ($adults - 2) * 1000; 
        }
        if ($children > 0) {
            $pricePerNight += $children * 500; 
        }

        $price = $days * $pricePerNight;
        $specialRequests = $validated['special_requests'] ?? null;

        $booking = DB::transaction(function () use ($room, $startDate, $finishDate, $days, $price, $adults, $children, $specialRequests) {
            $isBooked = Booking::where('room_id', $room->id)
                ->where(function ($query) use ($startDate, $finishDate) {
                    $query->where(function ($q) use ($startDate, $finishDate) {
                        $q->where('started_at', '<', $finishDate)
                            ->where('finished_at', '>', $startDate);
                    });
                })->exists();

            if ($isBooked) {
                throw new \Exception(__('booking.dates_already_taken'));
            }

            return Booking::create([
                'room_id' => $room->id,
                'user_id' => Auth::id(),
                'started_at' => $startDate,
                'finished_at' => $finishDate,
                'days' => $days,
                'adults' => $adults,
                'children' => $children,
                'price' => $price,
                'special_requests' => $specialRequests,
            ]);
        });

        try {
            Mail::to(Auth::user()->email)->send(new \App\Models\BookingConfirmation($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking confirmation email: ' . $e->getMessage());
        }

        return redirect()->route('bookings.index')->with('success', __('booking.room_booked_successfully'));
    }

    public function show(Booking $booking)
    {
        $this->authorize('view', $booking);

        $booking->load('room.hotel');
        return view('bookings.show', compact('booking'));
    }

    public function edit(Booking $booking)
    {
        $this->authorize('view', $booking);
        $booking->load('room.hotel');
        return view('bookings.edit', compact('booking'));
    }

    public function update(Request $request, Booking $booking)
    {
        \Log::info('Update method called for booking: ' . $booking->id);
        \Log::info('Request data: ', $request->all());

        if ($booking->user_id !== Auth::id() && Auth::user()->role !== 'admin') {
            \Log::error('User not authorized to update booking');
            abort(403);
        }

        if (Carbon::parse($booking->started_at)->isPast()) {
            \Log::error('Cannot update booking after check-in');
            abort(403);
        }

        $validated = $request->validate([
            'started_at' => 'required|date',
            'finished_at' => 'required|date|after:started_at',
            'adults' => 'required|integer|min:1|max:6',
            'children' => 'required|integer|min:0|max:4',
        ]);

        $startDate = Carbon::parse($validated['started_at']);
        $finishDate = Carbon::parse($validated['finished_at']);

        $isBooked = Booking::where('room_id', $booking->room_id)
            ->where('id', '!=', $booking->id)
            ->where(function ($query) use ($startDate, $finishDate) {
                $query->where(function ($q) use ($startDate, $finishDate) {
                    $q->where('started_at', '<', $finishDate)
                        ->where('finished_at', '>', $startDate);
                });
            })->exists();

        if ($isBooked) {
            return back()->withErrors(['booking_error' => __('booking.dates_already_taken')])->withInput();
        }

        $days = $startDate->diffInDays($finishDate);
        $adults = $validated['adults'] ?? 1;
        $children = $validated['children'] ?? 0;

        $pricePerNight = $booking->room->price;
        if ($adults > 2) {
            $pricePerNight += ($adults - 2) * 1000;
        }
        if ($children > 0) {
            $pricePerNight += $children * 500;
        }

        $price = $days * $pricePerNight;

        $booking->update([
            'started_at' => $startDate,
            'finished_at' => $finishDate,
            'days' => $days,
            'adults' => $adults,
            'children' => $children,
            'price' => $price,
        ]);

        try {
            $booking->refresh(); 
            Mail::to(Auth::user()->email)->send(new \App\Models\BookingConfirmation($booking));
        } catch (\Exception $e) {
            \Log::error('Failed to send booking update email: ' . $e->getMessage());
        }

        \Log::info('Booking updated successfully, redirecting to bookings.index');
        return redirect('/bookings')->with('success', __('booking.booking_updated_successfully'));
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', __('booking.booking_cancelled_successfully'));
    }
}

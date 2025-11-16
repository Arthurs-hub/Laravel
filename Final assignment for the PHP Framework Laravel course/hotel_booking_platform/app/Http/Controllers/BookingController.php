<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use App\Http\Requests\StoreBookingRequest;
use App\Services\BookingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;


/**
 * Контроллер для управления бронированиями
 * 
 * Обрабатывает HTTP запросы для создания, просмотра, редактирования и удаления бронирований
 */
class BookingController extends Controller
{
    /**
     * @param BookingService $bookingService Сервис для работы с бронированиями
     */
    public function __construct(
        private readonly BookingService $bookingService
    ) {
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

    /**
     * Создает новое бронирование
     * 
     * @param StoreBookingRequest $request Валидированный запрос
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(StoreBookingRequest $request)
    {
        try {
            $booking = $this->bookingService->createBooking(
                $request->validated(),
                Auth::user()
            );

            return redirect()->route('bookings.index')
                ->with('success', __('booking.room_booked_successfully'));
        } catch (\Exception $e) {
            return redirect()->route('bookings.index')
                ->withErrors(['booking_error' => $e->getMessage()])
                ->withInput();
        }
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

    /**
     * Обновляет существующее бронирование
     * 
     * @param Request $request HTTP запрос
     * @param Booking $booking Бронирование для обновления
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, Booking $booking)
    {
        if (!$this->bookingService->canUserEditBooking($booking, Auth::user())) {
            abort(403);
        }

        $validated = $request->validate([
            'started_at' => 'required|date',
            'finished_at' => 'required|date|after:started_at',
            'adults' => 'required|integer|min:1|max:6',
            'children' => 'required|integer|min:0|max:4',
        ]);

        try {
            $this->bookingService->updateBooking($booking, $validated, Auth::user());

            return redirect('/bookings')
                ->with('success', __('booking.booking_updated_successfully'));
        } catch (\Exception $e) {
            return back()
                ->withErrors(['booking_error' => $e->getMessage()])
                ->withInput();
        }
    }

    public function destroy(Booking $booking)
    {
        $this->authorize('delete', $booking);

        $booking->delete();

        return redirect()->route('bookings.index')->with('success', __('booking.booking_cancelled_successfully'));
    }
}

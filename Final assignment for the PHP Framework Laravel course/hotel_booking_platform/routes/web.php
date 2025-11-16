<?php

use App\Http\Controllers\Admin\BookingController as AdminBookingController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\HotelController as AdminHotelController;
use App\Http\Controllers\Admin\RoomController as AdminRoomController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\FacilityController as AdminFacilityController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\TwoFactorController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\HotelController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


require __DIR__ . '/auth.php';

Route::get('/', function () {
    return redirect()->route('hotels.index');
});

Route::get('/hotels', [HotelController::class, 'index'])->name('hotels.index');
Route::get('/hotels/{hotel}', [HotelController::class, 'show'])->name('hotels.show');


use App\Http\Controllers\Auth\CustomRegistrationController;

Route::middleware('guest')->group(function () {
    Route::get('/register', [CustomRegistrationController::class, 'create'])->name('register');
    Route::post('/register', [CustomRegistrationController::class, 'store'])->name('register.store');
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');


Route::middleware('auth')->group(function () {
    Route::get('/two-factor/method', [TwoFactorController::class, 'showMethodSelection'])->name('two-factor.method');
    Route::post('/two-factor/method', [TwoFactorController::class, 'selectMethod']);
    Route::get('/two-factor/verify', [TwoFactorController::class, 'showVerification'])->name('two-factor.verify');
    Route::post('/two-factor/verify', [TwoFactorController::class, 'verify']);
    Route::post('/two-factor/resend', [TwoFactorController::class, 'resendCode'])->name('two-factor.resend');
});

Route::middleware(['auth', 'two-factor'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/profile/toggle-2fa', [ProfileController::class, 'toggle2FA'])->name('profile.toggle-2fa');
    Route::delete('/profile/bookings/{booking}', [ProfileController::class, 'cancelBooking'])->name('profile.cancel-booking');

    Route::get('/bookings', [BookingController::class, 'index'])->name('bookings.index');
    Route::get('/bookings/create', [BookingController::class, 'create'])->name('bookings.create');
    Route::post('/bookings', [BookingController::class, 'store'])->name('bookings.store');
    Route::get('/bookings/{booking}', [BookingController::class, 'show'])->name('bookings.show');
    Route::get('/bookings/{booking}/edit', [BookingController::class, 'edit'])->name('bookings.edit');
    Route::patch('/bookings/{booking}', [BookingController::class, 'update'])->name('bookings.update');
    Route::delete('/bookings/{booking}', [BookingController::class, 'destroy'])->name('bookings.destroy');


    Route::get('/bookings/{booking}/review', [\App\Http\Controllers\ReviewController::class, 'create'])->name('reviews.create');
    Route::post('/bookings/{booking}/review', [\App\Http\Controllers\ReviewController::class, 'storeFromBooking'])->name('reviews.store');
});


Route::post('/api/rooms/check-availability', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'room_id' => 'required|exists:rooms,id',
        'started_at' => 'required|date|after:today',
        'finished_at' => 'required|date|after:started_at'
    ]);

    $room = \App\Models\Room::find($request->room_id);
    $days = \Carbon\Carbon::parse($request->started_at)->diffInDays($request->finished_at);

    $conflictingBookings = \App\Models\Booking::where('room_id', $request->room_id)
        ->where('status', '!=', 'cancelled')
        ->where(function ($query) use ($request) {
            $query->whereBetween('started_at', [$request->started_at, $request->finished_at])
                ->orWhereBetween('finished_at', [$request->started_at, $request->finished_at])
                ->orWhere(function ($q) use ($request) {
                    $q->where('started_at', '<=', $request->started_at)
                        ->where('finished_at', '>=', $request->finished_at);
                });
        })->exists();

    return response()->json([
        'available' => !$conflictingBookings,
        'days' => $days,
        'price_per_night' => $room->price,
        'total_price' => $room->price * $days
    ]);
});


Route::middleware(['auth', 'two-factor'])->get('/dashboard', function () {
    if (auth()->user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    } elseif (auth()->user()->role === 'manager') {
        return redirect()->route('manager.dashboard');
    }
    return redirect()->route('hotels.index');
})->name('dashboard');


Route::middleware(['auth', 'two-factor'])->prefix('manager')->name('manager.')->group(function () {
    Route::middleware('manager')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\ManagerDashboardController::class, 'index'])->name('dashboard');
        Route::resource('facilities', \App\Http\Controllers\Manager\FacilityController::class);
    });
});

Route::middleware(['auth', 'two-factor', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    Route::resource('hotels', AdminHotelController::class);
    Route::resource('rooms', AdminRoomController::class);
    Route::resource('bookings', AdminBookingController::class)->only(['index', 'show', 'update', 'destroy']);
    Route::post('/bookings/{booking}/approve', [AdminBookingController::class, 'approve'])->name('bookings.approve');
    Route::resource('users', AdminUserController::class);
    Route::resource('facilities', AdminFacilityController::class);


    Route::get('/hotels/{hotel}/rooms', [AdminRoomController::class, 'index'])->name('hotels.rooms.index');
    Route::post('/hotels/{hotel}/rooms', [AdminRoomController::class, 'storeForHotel'])->name('hotels.rooms.store');
});

Route::get('/hotels/{hotel}/reviews', [App\Http\Controllers\ReviewController::class, 'getHotelReviews'])->name('hotels.reviews.get');
Route::get('/rooms/{room}/reviews', [App\Http\Controllers\ReviewController::class, 'getRoomReviews'])->name('rooms.reviews.get');

Route::middleware(['auth'])->group(function () {
    Route::post('/hotels/{hotel}/reviews', [App\Http\Controllers\ReviewController::class, 'storeHotelReview'])->name('hotels.reviews.store');
    Route::post('/rooms/{room}/reviews', [App\Http\Controllers\ReviewController::class, 'storeRoomReview'])->name('rooms.reviews.store');
});

Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/reviews', [App\Http\Controllers\Admin\ReviewManagementController::class, 'index'])->name('admin.reviews.index');
    Route::patch('/reviews/{review}/approve', [App\Http\Controllers\Admin\ReviewManagementController::class, 'approve'])->name('admin.reviews.approve');
    Route::delete('/reviews/{review}', [App\Http\Controllers\Admin\ReviewManagementController::class, 'destroy'])->name('admin.reviews.destroy');

    Route::get('/system/status', [App\Http\Controllers\Admin\SystemStatusController::class, 'index'])->name('admin.system.status');
});

Route::middleware(['auth', 'manager'])->prefix('manager')->group(function () {
    Route::get('/reviews', [App\Http\Controllers\Manager\ReviewManagementController::class, 'index'])->name('manager.reviews.index');
    Route::patch('/reviews/{review}/approve', [App\Http\Controllers\Manager\ReviewManagementController::class, 'approve'])->name('manager.reviews.approve');
    Route::delete('/reviews/{review}', [App\Http\Controllers\Manager\ReviewManagementController::class, 'destroy'])->name('manager.reviews.destroy');
});

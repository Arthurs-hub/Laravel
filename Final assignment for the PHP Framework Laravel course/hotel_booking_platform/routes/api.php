<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\HotelController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

if (app()->environment(['local', 'testing'])) {
    Route::middleware('web')->group(function () {
        Route::post('/register', [AuthController::class, 'register']);
        Route::post('/login', [AuthController::class, 'login']);
        Route::post('/logout', [AuthController::class, 'logout']);

        Route::post('/two-factor/method', [\App\Http\Controllers\Auth\TwoFactorController::class, 'selectMethod']);
        Route::post('/two-factor/verify', [\App\Http\Controllers\Auth\TwoFactorController::class, 'verify']);
        Route::post('/two-factor/resend', [\App\Http\Controllers\Auth\TwoFactorController::class, 'resendCode']);
    });
}

Route::get('/hotels', [HotelController::class, 'index']);
Route::get('/hotels/{hotel}', [HotelController::class, 'show']);
Route::get('/countries', [HotelController::class, 'countries']);
Route::get('/facilities', function () {
    return response()->json([
        'success' => true,
        'data' => \App\Models\Facility::select('id', 'title', 'icon')->orderBy('title')->get()
    ]);
});

Route::middleware('web')->group(function () {
    Route::get('/profile', function () {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        return response()->json([
            'success' => true,
            'data' => auth()->user()
        ]);
    });
    
    Route::get('/bookings', function () {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        $bookings = \App\Models\Booking::where('user_id', auth()->id())
            ->with(['room.hotel'])
            ->orderBy('created_at', 'desc')
            ->get();
        return response()->json([
            'success' => true,
            'data' => $bookings
        ]);
    });
    
    Route::post('/bookings', function (Request $request) {
        if (!auth()->check()) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated'], 401);
        }
        
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'started_at' => 'required|date|after_or_equal:today',
            'finished_at' => 'required|date|after:started_at',
            'special_requests' => 'nullable|string|max:500'
        ]);
        
        $room = \App\Models\Room::findOrFail($request->room_id);
        $days = \Carbon\Carbon::parse($request->started_at)->diffInDays($request->finished_at);
        $totalPrice = $days * $room->price;
        
        $booking = \App\Models\Booking::create([
            'user_id' => auth()->id(),
            'room_id' => $request->room_id,
            'started_at' => $request->started_at,
            'finished_at' => $request->finished_at,
            'days' => $days,
            'price' => $totalPrice,
            'status' => 'confirmed',
            'special_requests' => $request->special_requests
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => $booking
        ], 201);
    });
    
    Route::delete('/bookings/{booking}', function (\App\Models\Booking $booking) {
        if (!auth()->check() || $booking->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $booking->update(['status' => 'cancelled']);
        
        return response()->json([
            'success' => true,
            'message' => 'Booking cancelled successfully'
        ]);
    });
    
    Route::put('/bookings/{booking}', function (Request $request, \App\Models\Booking $booking) {
        if (!auth()->check() || $booking->user_id !== auth()->id()) {
            return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
        }
        
        $request->validate([
            'started_at' => 'required|date|after_or_equal:today',
            'finished_at' => 'required|date|after:started_at',
            'special_requests' => 'nullable|string|max:500'
        ]);
        
        $room = $booking->room;
        $days = \Carbon\Carbon::parse($request->started_at)->diffInDays($request->finished_at);
        $totalPrice = $days * $room->price;
        
        $booking->update([
            'started_at' => $request->started_at,
            'finished_at' => $request->finished_at,
            'days' => $days,
            'price' => $totalPrice,
            'special_requests' => $request->special_requests
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'Booking updated successfully',
            'data' => $booking
        ]);
    });

    Route::middleware('admin')->prefix('admin')->group(function () {
        Route::get('/users', function () {
            $users = \App\Models\User::select('id', 'full_name', 'email', 'role', 'created_at')
                ->orderBy('created_at', 'desc')
                ->get();
            return response()->json(['success' => true, 'data' => $users]);
        });
        
        Route::get('/bookings', function (Request $request) {
            $query = \App\Models\Booking::with(['user:id,full_name,email', 'room.hotel:id,title']);
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('hotel_id')) {
                $query->whereHas('room', function ($q) use ($request) {
                    $q->where('hotel_id', $request->hotel_id);
                });
            }
            if ($request->filled('user_id')) {
                $query->where('user_id', $request->user_id);
            }
            
            $bookings = $query->orderBy('created_at', 'desc')->get();
            return response()->json(['success' => true, 'data' => $bookings]);
        });
        
        Route::post('/hotels', function (Request $request) {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'address' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'city' => 'nullable|string|max:100',
                'rating' => 'required|numeric|min:1|max:5',
                'manager_id' => 'nullable|exists:users,id',
                'poster_url' => 'nullable|url'
            ]);
            
            $data = $request->all();
            if (!isset($data['poster_url']) || empty($data['poster_url'])) {
                $data['poster_url'] = 'https://images.unsplash.com/photo-1566073771259-6a8506099945?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
            }
            
            $hotel = \App\Models\Hotel::create($data);

            \App\Models\Room::create([
                'hotel_id' => $hotel->id,
                'title' => 'Standard Room',
                'description' => 'Comfortable room with all amenities',
                'type' => 'standard',
                'price' => 100.00,
                'floor_area' => 25,
                'image_url' => 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80'
            ]);
            
            return response()->json(['success' => true, 'data' => $hotel], 201);
        });
        
        Route::put('/hotels/{hotel}', function (Request $request, \App\Models\Hotel $hotel) {
            $request->validate([
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'address' => 'required|string|max:255',
                'country' => 'required|string|max:100',
                'city' => 'nullable|string|max:100',
                'rating' => 'required|numeric|min:1|max:5',
                'manager_id' => 'nullable|exists:users,id',
                'poster_url' => 'nullable|url'
            ]);
            
            $hotel->update($request->all());
            return response()->json(['success' => true, 'data' => $hotel]);
        });
        
        Route::delete('/hotels/{hotel}', function (\App\Models\Hotel $hotel) {
            $hotel->delete();
            return response()->json(['success' => true, 'message' => 'Hotel deleted successfully']);
        });
        
        Route::post('/rooms', function (Request $request) {
            $request->validate([
                'hotel_id' => 'required|exists:hotels,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'type' => 'required|string|max:50',
                'price' => 'required|numeric|min:0',
                'floor_area' => 'required|numeric|min:0',
                'image_url' => 'nullable|url'
            ]);
            
            $data = $request->all();
            if (!isset($data['image_url']) || empty($data['image_url'])) {
                $data['image_url'] = 'https://images.unsplash.com/photo-1631049307264-da0ec9d70304?ixlib=rb-4.0.3&auto=format&fit=crop&w=1000&q=80';
            }
            
            $room = \App\Models\Room::create($data);
            return response()->json(['success' => true, 'data' => $room], 201);
        });
        
        Route::put('/rooms/{room}', function (Request $request, \App\Models\Room $room) {
            $request->validate([
                'hotel_id' => 'required|exists:hotels,id',
                'title' => 'required|string|max:255',
                'description' => 'required|string',
                'type' => 'required|string|max:50',
                'price' => 'required|numeric|min:0',
                'floor_area' => 'required|numeric|min:0',
                'image_url' => 'nullable|url'
            ]);
            
            $room->update($request->all());
            return response()->json(['success' => true, 'data' => $room]);
        });
        
        Route::delete('/rooms/{room}', function (\App\Models\Room $room) {
            $room->delete();
            return response()->json(['success' => true, 'message' => 'Room deleted successfully']);
        });
        
        Route::get('/stats', function () {
            $stats = [
                'total_users' => \App\Models\User::count(),
                'total_hotels' => \App\Models\Hotel::count(),
                'total_rooms' => \App\Models\Room::count(),
                'total_bookings' => \App\Models\Booking::count(),
                'active_bookings' => \App\Models\Booking::where('status', 'confirmed')->count(),
                'cancelled_bookings' => \App\Models\Booking::where('status', 'cancelled')->count(),
                'revenue' => \App\Models\Booking::where('status', 'confirmed')->sum('price')
            ];
            return response()->json(['success' => true, 'data' => $stats]);
        });
    });

    Route::middleware('manager')->prefix('manager')->group(function () {
        Route::get('/hotels', function () {
            $hotels = \App\Models\Hotel::where('manager_id', auth()->id())
                ->with(['rooms'])
                ->get();
            return response()->json(['success' => true, 'data' => $hotels]);
        });
        
        Route::get('/bookings', function (Request $request) {
            $query = \App\Models\Booking::whereHas('room.hotel', function ($q) {
                $q->where('manager_id', auth()->id());
            })->with(['user:id,full_name,email', 'room.hotel:id,title']);
            
            if ($request->filled('status')) {
                $query->where('status', $request->status);
            }
            if ($request->filled('hotel_id')) {
                $query->whereHas('room', function ($q) use ($request) {
                    $q->where('hotel_id', $request->hotel_id);
                });
            }
            
            $bookings = $query->orderBy('created_at', 'desc')->get();
            return response()->json(['success' => true, 'data' => $bookings]);
        });
        
        Route::put('/bookings/{booking}', function (Request $request, \App\Models\Booking $booking) {
            if ($booking->room->hotel->manager_id !== auth()->id()) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }
            
            $request->validate(['status' => 'required|in:confirmed,cancelled,pending']);
            $booking->update(['status' => $request->status]);
            
            return response()->json(['success' => true, 'message' => 'Booking status updated']);
        });
    });
});

Route::post('/update-timezone', function (Request $request) {
    $request->validate([
        'timezone' => ['required', 'string', 'max:50', 'timezone']
    ]);

    if (auth()->check()) {
        auth()->user()->update(['timezone' => $request->timezone]);
        return response()->json(['success' => true]);
    }

    return response()->json(['success' => false], 401);
})->middleware('web');

Route::prefix('rooms')->group(function () {
    Route::post('/check-availability', function (Request $request) {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
            'started_at' => 'required|date|after_or_equal:today',
            'finished_at' => 'required|date|after:started_at',
        ]);

        $room = \App\Models\Room::findOrFail($request->room_id);
        $startDate = \Carbon\Carbon::parse($request->started_at);
        $endDate = \Carbon\Carbon::parse($request->finished_at);

        $isAvailable = !\App\Models\Booking::where('room_id', $room->id)
            ->where(function ($query) use ($startDate, $endDate) {
                $query->where(function ($q) use ($startDate, $endDate) {
                    $q->where('started_at', '<', $endDate)
                        ->where('finished_at', '>', $startDate);
                });
            })->exists();

        $days = $startDate->diffInDays($endDate);
        $totalPrice = $days * $room->price;

        return response()->json([
            'available' => $isAvailable,
            'days' => $days,
            'price_per_night' => $room->price,
            'total_price' => $totalPrice,
        ]);
    });
});
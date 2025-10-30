<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Room;
use App\Models\Facility;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $query = Room::with(['hotel', 'facilities']);

        if (auth()->user()->isManager() && !auth()->user()->isAdmin()) {
            $hotel = auth()->user()->managedHotel;
            if ($hotel) {
                $query->where('hotel_id', $hotel->id);
            } else {
                $query->whereRaw('1 = 0'); 
            }
        }

        $rooms = $query->latest()->paginate(10);
        return view('admin.rooms.index', compact('rooms'));
    }

    public function create()
    {
        if (auth()->user()->isManager() && !auth()->user()->isAdmin()) {
            $hotels = Hotel::where('manager_id', auth()->id())->get();
        } else {
            $hotels = Hotel::all();
        }

        $facilities = Facility::all();
        return view('admin.rooms.create', compact('hotels', 'facilities'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url|max:100',
            'floor_area' => 'required|numeric|min:0',
            'type' => 'required|string|max:100',
            'price' => 'required|integer|min:0',
            'hotel_id' => 'required|exists:hotels,id',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        $room = Room::create($validated);

        if (isset($validated['facilities'])) {
            $room->facilities()->sync($validated['facilities']);
        }

        return redirect()->route('admin.rooms.index')->with('success', __('admin.room_created_successfully'));
    }

    public function show(Room $room)
    {
        $room->load(['hotel', 'facilities', 'bookings.user']);
        return view('admin.rooms.show', compact('room'));
    }

    public function edit(Room $room)
    {
        if (auth()->user()->isManager() && !auth()->user()->isAdmin()) {
            if ($room->hotel->manager_id !== auth()->id()) {
                abort(403, __('admin.no_permission_edit_room'));
            }
            $hotels = Hotel::where('manager_id', auth()->id())->get();
        } else {
            $hotels = Hotel::all();
        }

        $facilities = Facility::all();
        $room->load('facilities');
        return view('admin.rooms.edit', compact('room', 'facilities', 'hotels'));
    }

    public function update(Request $request, Room $room)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url|max:100',
            'floor_area' => 'required|numeric|min:0',
            'type' => 'required|string|max:100',
            'price' => 'required|integer|min:0',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        $room->update($validated);

        if (isset($validated['facilities'])) {
            $room->facilities()->sync($validated['facilities']);
        }

        return redirect()->route('admin.rooms.index')->with('success', __('admin.room_updated_successfully'));
    }

    public function destroy(Room $room)
    {
        $room->delete();
        return redirect()->route('admin.rooms.index')->with('success', __('admin.room_deleted_successfully'));
    }

    public function storeForHotel(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url|max:500',
            'floor_area' => 'required|numeric|min:0',
            'type' => 'required|string|max:100',
            'price' => 'required|integer|min:0',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        $validated['hotel_id'] = $hotel->id;
        $room = Room::create($validated);

        if (isset($validated['facilities'])) {
            $room->facilities()->sync($validated['facilities']);
        }

        return redirect("/admin/hotels/{$hotel->id}/rooms")->with('success', __('admin.room_created_successfully'));
    }
}

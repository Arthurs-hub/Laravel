<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Hotel;
use App\Models\Facility;
use Illuminate\Http\Request;

class HotelController extends Controller
{
    public function index()
    {
        $hotels = Hotel::with('rooms')->latest()->paginate(50);
        return view('admin.hotels.index', compact('hotels'));
    }

    public function create()
    {
        $facilities = Facility::all();
        $managers = \App\Models\User::orderBy('full_name')->get();
        return view('admin.hotels.create', compact('facilities', 'managers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'required|string',
            'poster_url' => 'nullable|url|max:100',
            'address' => 'required|string|max:500',
            'country' => 'required|string|max:100',
            'manager_id' => 'nullable|exists:users,id',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        if (isset($validated['manager_id']) && $validated['manager_id']) {
            $manager = \App\Models\User::find($validated['manager_id']);
            if ($manager && $manager->role !== 'manager') {
                $manager->update(['role' => 'manager']);
            }
        }

        $hotel = Hotel::create($validated);

        if (isset($validated['facilities'])) {
            $hotel->facilities()->sync($validated['facilities']);
        }

        return redirect()->route('admin.hotels.index')->with('success', __('admin.hotel_created_successfully'));
    }

    public function show(Hotel $hotel)
    {
        $hotel->load(['rooms', 'facilities']);
        return view('admin.hotels.show', compact('hotel'));
    }

    public function edit(Hotel $hotel)
    {
        $facilities = Facility::all();
        $managers = \App\Models\User::orderBy('full_name')->get();
        $hotel->load('facilities');
        return view('admin.hotels.edit', compact('hotel', 'facilities', 'managers'));
    }

    public function update(Request $request, Hotel $hotel)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100',
            'description' => 'nullable|string',
            'poster_url' => 'nullable|url|max:100',
            'address' => 'required|string|max:500',
            'country' => 'nullable|string|max:100',
            'manager_id' => 'nullable|exists:users,id',
            'facilities' => 'array',
            'facilities.*' => 'exists:facilities,id'
        ]);

        if (isset($validated['manager_id']) && $validated['manager_id']) {
            $newManager = \App\Models\User::find($validated['manager_id']);
            if ($newManager && $newManager->role !== 'manager') {
                $newManager->update(['role' => 'manager']);
            }
        }

        if ($hotel->manager_id && (!isset($validated['manager_id']) || !$validated['manager_id'])) {
            $oldManager = \App\Models\User::find($hotel->manager_id);
            if ($oldManager) {
                
                $otherHotels = Hotel::where('manager_id', $oldManager->id)
                    ->where('id', '!=', $hotel->id)
                    ->count();

                if ($otherHotels === 0) {
                    $oldManager->update(['role' => 'user']);
                }
            }
        }

        $hotel->update($validated);

        if (isset($validated['facilities'])) {
            $hotel->facilities()->sync($validated['facilities']);
        }

        return redirect()->route('admin.hotels.index')->with('success', __('admin.hotel_updated_successfully'));
    }

    public function destroy(Hotel $hotel)
    {
        $hotel->delete();
        return redirect()->route('admin.hotels.index')->with('success', __('admin.hotel_deleted_successfully'));
    }
}

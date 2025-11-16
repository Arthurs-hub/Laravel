<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use Illuminate\Http\Request;

class FacilityController extends Controller
{
    public function index()
    {
        $facilities = Facility::select('id', 'title', 'icon', 'created_at', 'updated_at')
            ->orderBy('title')
            ->get();
            
        return response()->json([
            'success' => true,
            'data' => $facilities
        ]);
    }

    public function show(Facility $facility)
    {
        return response()->json([
            'success' => true,
            'data' => $facility
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:100|unique:facilities,title',
        ]);

        $facility = Facility::create([
            'title' => $request->title,
            'icon' => $request->icon ?? 'fas fa-star'
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Facility created successfully',
            'data' => $facility
        ], 201);
    }

    public function update(Request $request, Facility $facility)
    {
        $request->validate([
            'title' => 'required|string|max:100|unique:facilities,title,' . $facility->id,
        ]);

        $facility->update([
            'title' => $request->title,
            'icon' => $request->icon ?? $facility->icon
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Facility updated successfully',
            'data' => $facility
        ]);
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        
        return response()->json([
            'success' => true,
            'message' => 'Facility deleted successfully'
        ]);
    }
}
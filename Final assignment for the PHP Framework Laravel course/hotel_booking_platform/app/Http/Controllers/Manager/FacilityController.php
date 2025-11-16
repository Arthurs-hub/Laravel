<?php

namespace App\Http\Controllers\Manager;

use App\Http\Controllers\Controller;
use App\Models\Facility;
use App\Models\Hotel;
use Illuminate\Http\Request;

class FacilityController extends Controller
{


    public function index()
    {
        $facilities = Facility::latest()->paginate(10);
        return view('manager.facilities.index', compact('facilities'));
    }

    public function create()
    {
        return view('manager.facilities.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100|unique:facilities,title',
        ]);

        Facility::create($validated);

        return redirect()->route('manager.facilities.index')->with('success', __('admin.facility_created_successfully'));
    }

    public function show(Facility $facility)
    {
        return view('manager.facilities.show', compact('facility'));
    }

    public function edit(Facility $facility)
    {
        return view('manager.facilities.edit', compact('facility'));
    }

    public function update(Request $request, Facility $facility)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:100|unique:facilities,title,' . $facility->id,
        ]);

        $facility->update($validated);

        return redirect()->route('manager.facilities.index')->with('success', __('admin.facility_updated_successfully'));
    }

    public function destroy(Facility $facility)
    {
        $facility->delete();
        return redirect()->route('manager.facilities.index')->with('success', __('admin.facility_deleted_successfully'));
    }
}
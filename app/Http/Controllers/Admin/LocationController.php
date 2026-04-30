<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    public function index()
    {
        $allLocations = Location::with('parent')->get();
        $states = Location::states()->get();
        return view('admin.locations.index', compact('allLocations', 'states'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:state,city,postcode',
            'code' => 'nullable|string|max:10',
            'parent_id' => 'nullable|exists:locations,id',
        ]);

        Location::create($validated);
        return redirect()->back()->with('success', 'Location created successfully.');
    }

    public function edit(Location $location)
    {
        $states = Location::states()->get();
        return view('admin.locations.edit', compact('location', 'states'));
    }

    public function update(Request $request, Location $location)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'type' => 'required|string|in:state,city,postcode',
            'code' => 'nullable|string|max:10',
            'parent_id' => 'nullable|exists:locations,id',
            'is_enabled' => 'required|boolean',
        ]);

        $location->update($validated);
        return redirect()->route('admin.locations.index')->with('success', 'Location updated successfully.');
    }

    public function destroy(Location $location)
    {
        $location->delete();
        return redirect()->back()->with('success', 'Location deleted successfully.');
    }
}

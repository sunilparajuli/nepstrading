<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ShippingZone;
use App\Models\ShippingLocation;
use App\Models\ShippingRate;
use Illuminate\Http\Request;

class ShippingController extends Controller
{
    public function index()
    {
        $zones = ShippingZone::with(['locations', 'rates'])->get();
        return view('admin.shipping.index', compact('zones'));
    }

    public function storeZone(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
        ]);

        ShippingZone::create($validated);
        return redirect()->back()->with('success', 'Shipping zone created.');
    }

    public function storeRate(Request $request, ShippingZone $zone)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'cost' => 'required|numeric|min:0',
        ]);

        $zone->rates()->create($validated);
        return redirect()->back()->with('success', 'Shipping rate added.');
    }

    public function storeLocation(Request $request, ShippingZone $zone)
    {
        $validated = $request->validate([
            'type' => 'required|string',
            'code' => 'required|string',
        ]);

        $zone->locations()->create($validated);
        return redirect()->back()->with('success', 'Location added to zone.');
    }

    public function destroyZone(ShippingZone $zone)
    {
        $zone->delete();
        return redirect()->back()->with('success', 'Zone deleted.');
    }

    public function destroyRate(ShippingRate $rate)
    {
        $rate->delete();
        return redirect()->back()->with('success', 'Rate deleted.');
    }

    public function destroyLocation(ShippingLocation $location)
    {
        $location->delete();
        return redirect()->back()->with('success', 'Location removed.');
    }
}

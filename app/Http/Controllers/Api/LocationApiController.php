<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ShippingLocation;
use Illuminate\Http\Request;

class LocationApiController extends Controller
{
    /**
     * Get unique Australian states.
     */
    public function getStates()
    {
        $states = ShippingLocation::where('type', 'state')
            ->orderBy('code', 'asc')
            ->pluck('code')
            ->unique()
            ->values();

        return response()->json($states);
    }

    /**
     * Get postcodes for a specific state.
     */
    public function getPostcodes(Request $request)
    {
        $request->validate([
            'state' => 'required|string'
        ]);

        $stateCode = $request->state;

        // Find the zone associated with this state
        $zoneIds = ShippingLocation::where('type', 'state')
            ->where('code', $stateCode)
            ->pluck('shipping_zone_id');

        if ($zoneIds->isEmpty()) {
            return response()->json([]);
        }

        // Get postcodes linked to those zones
        $postcodes = ShippingLocation::where('type', 'postcode')
            ->whereIn('shipping_zone_id', $zoneIds)
            ->orderBy('code', 'asc')
            ->pluck('code')
            ->unique()
            ->values();

        return response()->json($postcodes);
    }
}

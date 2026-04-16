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

    /**
     * Calculate shipping cost based on location and subtotal.
     */
    public function calculateShipping(Request $request)
    {
        $request->validate([
            'state' => 'required|string',
            'postcode' => 'required|string',
            'subtotal' => 'required|numeric'
        ]);

        $subtotal = $request->subtotal;
        $state = \App\Helpers\LocationHelper::normalizeState($request->state);
        $postcode = $request->postcode;

        // 1. Free shipping threshold ($69)
        if ($subtotal >= 69) {
            return response()->json([
                'shipping_cost' => 0,
                'shipping_name' => 'Free Shipping',
                'is_free' => true,
                'threshold_met' => true
            ]);
        }

        // 2. Fetch all postcode-type locations once
        $postcodeLocations = ShippingLocation::where('type', 'postcode')->with('zone.rates')->get();
        $foundLocation = null;

        // Try to find zone by postcode
        foreach ($postcodeLocations as $loc) {
            if (\App\Helpers\LocationHelper::matchPostcode($postcode, $loc->code)) {
                $foundLocation = $loc;
                break;
            }
        }

        // Try to find zone by state
        if (!$foundLocation) {
            $foundLocation = ShippingLocation::where('type', 'state')
                ->where('code', $state)
                ->first();
        }

        if ($foundLocation && $foundLocation->zone) {
            $rate = \App\Models\ShippingRate::where('shipping_zone_id', $foundLocation->shipping_zone_id)
                ->orderBy('cost', 'asc')
                ->first();

            if ($rate) {
                return response()->json([
                    'shipping_cost' => (float)$rate->cost,
                    'shipping_name' => $rate->name . ' (' . $foundLocation->zone->name . ')',
                    'is_free' => false,
                    'threshold_met' => false
                ]);
            }
        }

        return response()->json([
            'shipping_cost' => 15.00, // Fallback
            'shipping_name' => 'Standard Shipping',
            'is_free' => false,
            'threshold_met' => false
        ], 200);
    }
}

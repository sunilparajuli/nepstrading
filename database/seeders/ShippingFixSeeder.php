<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingLocation;
use App\Models\ShippingZone;

class ShippingFixSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Clean up Zone 2 (Perth) - Consolidate hundreds of single codes into ranges
        $perthZone = ShippingZone::where('name', 'LIKE', '%Perth%')->first();
        if ($perthZone) {
            // Delete existing postcode locations for this zone to avoid duplicates or messy data
            ShippingLocation::where('shipping_zone_id', $perthZone->id)
                            ->where('type', 'postcode')
                            ->delete();

            $patterns = [
                '6000-6001', '6003-6012', '6015-6027', '6029', '6032', '6036', 
                '6050-6055', '6057-6068', '6077', '6079', '6090', '6100-6110', 
                '6147-6162', '6800', '6831-6832', '6840-6846', '6848-6850', '6865', 
                '6872', '6892', '6900-6902', '6904-6907', '6910-6924', '6926', 
                '6929', '6931-6937', '6939', '6941-6947', '6951-6957', '6959-6961', 
                '6963-6964', '6970', '6979-6992', '6997', 
                '6014*', '6056*', '6076*', '6111-6112*', '6163-6166*'
            ];

            foreach ($patterns as $pattern) {
                ShippingLocation::create([
                    'shipping_zone_id' => $perthZone->id,
                    'type' => 'postcode',
                    'code' => $pattern
                ]);
            }
        }

        // 2. Ensure state codes are standardized to abbreviations (e.g. Victoria -> VIC)
        // This helps the new LocationHelper match them reliably
        $locations = ShippingLocation::where('type', 'state')->get();
        foreach ($locations as $loc) {
            $normalized = \App\Helpers\LocationHelper::normalizeState($loc->code);
            if ($normalized && $normalized !== $loc->code) {
                $loc->update(['code' => $normalized]);
            }
        }
    }
}

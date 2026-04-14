<?php

namespace Database\Seeders;

use App\Models\ShippingZone;
use App\Models\ShippingLocation;
use App\Models\ShippingRate;
use Illuminate\Database\Seeder;

class WordPressShippingSyncSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Zone: WP Migrated Free Shipping
        $zone6 = ShippingZone::updateOrCreate(
            ['name' => 'WP Migrated Free Shipping'],
            ['is_enabled' => true]
        );

        ShippingRate::updateOrCreate(
            ['shipping_zone_id' => $zone6->id, 'name' => 'Free Shipping'],
            ['cost' => 0.00, 'type' => 'free_shipping']
        );

        $freePostcodes = [3174, 3975, 3978, 3977, 3805];
        foreach ($freePostcodes as $code) {
            ShippingLocation::updateOrCreate(
                ['shipping_zone_id' => $zone6->id, 'type' => 'postcode', 'code' => (string)$code]
            );
        }

        // 2. Zone: WP Migrated Flat Rate
        $zone7 = ShippingZone::updateOrCreate(
            ['name' => 'WP Migrated Flat Rate'],
            ['is_enabled' => true]
        );

        ShippingRate::updateOrCreate(
            ['shipping_zone_id' => $zone7->id, 'name' => 'Flat Rate'],
            ['cost' => 15.00, 'type' => 'flat_rate'] // Assuming 15 based on "Rest of Australia" or WP data
        );

        // Add VIC state to Zone 7
        ShippingLocation::updateOrCreate(
            ['shipping_zone_id' => $zone7->id, 'type' => 'state', 'code' => 'VIC']
        );

        // Add Range 3000-3300 to Zone 7
        for ($i = 3000; $i <= 3300; $i++) {
            ShippingLocation::updateOrCreate(
                ['shipping_zone_id' => $zone7->id, 'type' => 'postcode', 'code' => (string)$i]
            );
        }
    }
}

<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ShippingZone;
use App\Models\ShippingLocation;
use App\Models\ShippingRate;

class ShippingSeeder extends Seeder
{
    public function run(): void
    {
        $zones = [
            [
                'name' => 'NSW & ACT',
                'locations' => [
                    ['type' => 'state', 'code' => 'NSW'],
                    ['type' => 'state', 'code' => 'ACT'],
                ],
                'rates' => [
                    ['name' => 'Standard Shipping', 'cost' => 10, 'type' => 'flat_rate'],
                    ['name' => 'Express Shipping', 'cost' => 20, 'type' => 'flat_rate'],
                ]
            ],
            [
                'name' => 'Victoria',
                'locations' => [
                    ['type' => 'state', 'code' => 'VIC'],
                ],
                'rates' => [
                    ['name' => 'Standard Shipping', 'cost' => 12, 'type' => 'flat_rate'],
                ]
            ],
            [
                'name' => 'Rest of Australia',
                'locations' => [
                    ['type' => 'state', 'code' => 'QLD'],
                    ['type' => 'state', 'code' => 'WA'],
                    ['type' => 'state', 'code' => 'SA'],
                    ['type' => 'state', 'code' => 'TAS'],
                    ['type' => 'state', 'code' => 'NT'],
                ],
                'rates' => [
                    ['name' => 'Standard Shipping', 'cost' => 15, 'type' => 'flat_rate'],
                ]
            ],
        ];

        foreach ($zones as $zoneData) {
            $zone = ShippingZone::create(['name' => $zoneData['name']]);

            foreach ($zoneData['locations'] as $loc) {
                ShippingLocation::create(array_merge($loc, ['shipping_zone_id' => $zone->id]));
            }

            foreach ($zoneData['rates'] as $rate) {
                ShippingRate::create(array_merge($rate, ['shipping_zone_id' => $zone->id]));
            }
        }
    }
}

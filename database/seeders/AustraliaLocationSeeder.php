<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Location;

class AustraliaLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            'New South Wales' => 'NSW',
            'Victoria' => 'VIC',
            'Queensland' => 'QLD',
            'Western Australia' => 'WA',
            'South Australia' => 'SA',
            'Tasmania' => 'TAS',
            'Australian Capital Territory' => 'ACT',
            'Northern Territory' => 'NT',
        ];

        $locations = [
            'NSW' => ['Sydney', 'Parramatta', 'Wollongong', 'Newcastle', 'Central Coast', 'Maitland', 'Port Macquarie', 'Orange'],
            'VIC' => ['Melbourne', 'Geelong', 'Ballarat', 'Bendigo', 'Frankston', 'Shepparton', 'Wodonga', 'Mildura'],
            'QLD' => ['Brisbane', 'Gold Coast', 'Sunshine Coast', 'Townsville', 'Cairns', 'Toowoomba', 'Mackay', 'Rockhampton'],
            'WA' => ['Perth', 'Fremantle', 'Mandurah', 'Bunbury', 'Joondalup', 'Kalgoorlie', 'Geraldton', 'Albany'],
            'SA' => ['Adelaide', 'Glenelg', 'Mount Gambier', 'Whyalla', 'Gawler', 'Murray Bridge', 'Port Augusta'],
            'TAS' => ['Hobart', 'Launceston', 'Devonport', 'Burnie', 'Kingston', 'Ulverstone'],
            'ACT' => ['Canberra', 'Belconnen', 'Tuggeranong', 'Gungahlin'],
            'NT' => ['Darwin', 'Alice Springs', 'Palmerston', 'Katherine', 'Nhulunbuy'],
        ];

        foreach ($states as $name => $code) {
            $state = Location::updateOrCreate(
                ['name' => $name, 'type' => 'state'],
                ['code' => $code, 'is_enabled' => true]
            );

            if (isset($locations[$code])) {
                foreach ($locations[$code] as $cityName) {
                    Location::updateOrCreate(
                        ['name' => $cityName, 'type' => 'city', 'parent_id' => $state->id],
                        ['is_enabled' => true]
                    );
                }
            }
        }
    }
}

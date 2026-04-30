<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AustraliaLocationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $states = [
            ['name' => 'New South Wales', 'code' => 'NSW', 'cities' => ['Sydney', 'Newcastle', 'Wollongong', 'Central Coast', 'Maitland']],
            ['name' => 'Victoria', 'code' => 'VIC', 'cities' => ['Melbourne', 'Geelong', 'Ballarat', 'Bendigo', 'Shepparton']],
            ['name' => 'Queensland', 'code' => 'QLD', 'cities' => ['Brisbane', 'Gold Coast', 'Sunshine Coast', 'Townsville', 'Cairns']],
            ['name' => 'Western Australia', 'code' => 'WA', 'cities' => ['Perth', 'Rockingham', 'Mandurah', 'Bunbury', 'Kalgoorlie']],
            ['name' => 'South Australia', 'code' => 'SA', 'cities' => ['Adelaide', 'Mount Gambier', 'Whyalla', 'Gawler', 'Murray Bridge']],
            ['name' => 'Tasmania', 'code' => 'TAS', 'cities' => ['Hobart', 'Launceston', 'Devonport', 'Burnie', 'Kingston']],
            ['name' => 'Australian Capital Territory', 'code' => 'ACT', 'cities' => ['Canberra']],
            ['name' => 'Northern Territory', 'code' => 'NT', 'cities' => ['Darwin', 'Alice Springs', 'Palmerston', 'Katherine']],
        ];

        foreach ($states as $stateData) {
            $state = \App\Models\Location::updateOrCreate(
                ['name' => $stateData['name'], 'type' => 'state'],
                ['code' => $stateData['code']]
            );

            foreach ($stateData['cities'] as $cityName) {
                \App\Models\Location::updateOrCreate(
                    ['name' => $cityName, 'type' => 'city', 'parent_id' => $state->id]
                );
            }
        }
    }
}

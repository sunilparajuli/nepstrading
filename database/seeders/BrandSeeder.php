<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $brands = [
            ['name' => 'Britannia', 'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/d/d4/Britannia_Industries_logo.svg/1200px-Britannia_Industries_logo.svg.png'],
            ['name' => "Haldiram's", 'logo' => 'https://upload.wikimedia.org/wikipedia/en/thumb/d/d3/Haldiram%27s_Logo.svg/1200px-Haldiram%27s_Logo.svg.png'],
            ['name' => 'Daawat', 'logo' => 'https://daawat.com/wp-content/themes/daawat/images/logo.png'],
            ['name' => 'MDH Masala', 'logo' => 'https://mdhspices.com/wp-content/uploads/2018/06/logo.png'],
            ['name' => 'GRB', 'logo' => 'https://grb.co.in/wp-content/uploads/2021/04/grb-logo-1.png'],
            ['name' => 'Shan Foods', 'logo' => 'https://www.shanfoods.com/wp-content/themes/shanfoods/assets/images/logo.png'],
            ['name' => 'Bikano', 'logo' => 'https://www.bikano.com/img/logo.png'],
            ['name' => 'India Gate', 'logo' => 'https://indiagatefoods.com/wp-content/uploads/2022/06/india-gate-logo.png'],
        ];

        foreach ($brands as $index => $brand) {
            \App\Models\Brand::create([
                'name' => $brand['name'],
                'logo' => $brand['logo'],
                'sort_order' => $index * 10,
                'is_enabled' => true,
            ]);
        }
    }
}

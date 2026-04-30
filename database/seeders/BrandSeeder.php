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
            ['name' => 'Britannia', 'logo' => 'https://placehold.co/200x80?text=Britannia'],
            ['name' => "Haldiram's", 'logo' => 'https://placehold.co/200x80?text=Haldirams'],
            ['name' => 'Daawat', 'logo' => 'https://placehold.co/200x80?text=Daawat'],
            ['name' => 'MDH Masala', 'logo' => 'https://placehold.co/200x80?text=MDH+Masala'],
            ['name' => 'GRB', 'logo' => 'https://placehold.co/200x80?text=GRB'],
            ['name' => 'Shan Foods', 'logo' => 'https://placehold.co/200x80?text=Shan+Foods'],
            ['name' => 'Bikano', 'logo' => 'https://placehold.co/200x80?text=Bikano'],
            ['name' => 'India Gate', 'logo' => 'https://placehold.co/200x80?text=India+Gate'],
        ];

        foreach ($brands as $index => $brand) {
            \App\Models\Brand::updateOrCreate(
                ['name' => $brand['name']],
                [
                    'logo' => $brand['logo'],
                    'sort_order' => $index * 10,
                    'is_enabled' => true,
                ]
            );
        }
    }
}

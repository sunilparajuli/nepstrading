<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSection;

class AddNewHomepageSectionsSeeder extends Seeder
{
    public function run(): void
    {
        $sections = [
            [
                'type' => 'weekly_special',
                'title' => 'Weekly Special',
                'subtitle' => 'Unmissable deals and discounts on your favorite items.',
                'data' => ['limit' => 8, 'columns' => 4],
                'order' => 6,
                'is_active' => true,
            ],
            [
                'type' => 'featured_products',
                'title' => 'Featured Products',
                'subtitle' => 'Our hand-picked selection of premium quality items.',
                'data' => ['limit' => 8, 'columns' => 4],
                'order' => 7,
                'is_active' => true,
            ],
            [
                'type' => 'new_products',
                'title' => 'New Arrival',
                'subtitle' => 'Freshly added to our store. Grab them while they are fresh!',
                'data' => ['limit' => 8, 'columns' => 4],
                'order' => 8,
                'is_active' => true,
            ],
        ];

        foreach ($sections as $sectionData) {
            // Check if section already exists by title to avoid duplicates
            if (!HomepageSection::where('title', $sectionData['title'])->exists()) {
                HomepageSection::create($sectionData);
            }
        }
    }
}

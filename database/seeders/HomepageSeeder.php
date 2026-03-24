<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\HomepageSection;

class HomepageSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Hero Section
        HomepageSection::create([
            'type' => 'hero',
            'title' => 'The Finest Australian Produce',
            'subtitle' => 'Sourced directly from local farmers, delivered with care to your doorstep.',
            'order' => 1,
            'is_active' => true,
            'data' => [
                'button_text' => 'Shop All Products',
                'button_link' => '/products',
                'bg_image' => 'https://images.unsplash.com/photo-1542838132-92c53300491e?auto=format&fit=crop&q=80&w=1920'
            ]
        ]);

        // 2. Featured Products
        HomepageSection::create([
            'type' => 'featured_products',
            'title' => 'Seasonal Favorites',
            'subtitle' => 'Hand-picked selections of the best seasonal items this week.',
            'order' => 2,
            'is_active' => true,
            'data' => [
                'limit' => 4,
                'columns' => 4
            ]
        ]);

        // 3. Category Grid
        HomepageSection::create([
            'type' => 'categories',
            'title' => 'Shop by Category',
            'subtitle' => 'Explore our wide range of premium grocery categories.',
            'order' => 3,
            'is_active' => true,
            'data' => [
                'limit' => 3
            ]
        ]);

        // 4. Popular Products
        HomepageSection::create([
            'type' => 'popular_products',
            'title' => 'Trending Now',
            'subtitle' => 'What everyone is loving lately.',
            'order' => 4,
            'is_active' => true,
            'data' => [
                'limit' => 8,
                'columns' => 4
            ]
        ]);
    }
}

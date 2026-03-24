<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\Category;
use App\Models\Product;

class SnackProductSeeder extends Seeder
{
    public function run(): void
    {
        $faker = \Faker\Factory::create();
        
        $category = Category::firstOrCreate(
            ['slug' => 'snacks'],
            ['name' => 'Snacks']
        );

        for ($i = 0; $i < 100; $i++) {
            $name = $faker->words(rand(2, 4), true);
            $slug = Str::slug($name);
            
            // Handle duplicate slugs
            $baseSlug = $slug;
            $counter = 1;
            while (Product::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $sku = strtoupper($faker->bothify('SNACK-####'));
            while (Product::where('sku', $sku)->exists()) {
                $sku = strtoupper($faker->bothify('SNACK-####'));
            }

            $product = Product::create([
                'name' => ucwords($name),
                'slug' => $slug,
                'description' => $faker->paragraph(3),
                'short_description' => $faker->sentence(),
                'price' => $faker->randomFloat(2, 2, 50),
                'sku' => $sku,
                'stock_status' => 'instock',
                'manage_stock' => true,
                'stock_quantity' => rand(10, 500),
                'status' => 'publish',
                'image' => 'https://images.unsplash.com/photo-1599490659213-e2b9527ec087?auto=format&fit=crop&q=80&w=800', // Default snack image
                'is_popular' => $faker->boolean(20),
                'is_seasonal' => $faker->boolean(10)
            ]);

            $product->categories()->sync([$category->id]);
        }
    }
}

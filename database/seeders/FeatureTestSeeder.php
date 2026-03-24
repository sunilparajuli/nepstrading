<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Attribute;
use App\Models\AttributeTerm;
use Illuminate\Support\Str;

class FeatureTestSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Create Color Attribute
        $colorAttr = Attribute::updateOrCreate(
            ['slug' => 'color'],
            ['name' => 'Color', 'type' => 'color']
        );

        $colors = [
            ['name' => 'Red', 'value' => '#ef4444'],
            ['name' => 'Blue', 'value' => '#3b82f6'],
            ['name' => 'Green', 'value' => '#22c55e'],
            ['name' => 'Black', 'value' => '#000000'],
        ];

        $colorTerms = [];
        foreach ($colors as $c) {
            $colorTerms[] = AttributeTerm::updateOrCreate(
                ['attribute_id' => $colorAttr->id, 'slug' => Str::slug($c['name'])],
                ['name' => $c['name'], 'value' => $c['value']]
            );
        }

        // 2. Create Size Attribute
        $sizeAttr = Attribute::updateOrCreate(
            ['slug' => 'size'],
            ['name' => 'Size', 'type' => 'select']
        );

        $sizes = ['S', 'M', 'L', 'XL'];
        $sizeTerms = [];
        foreach ($sizes as $s) {
            $sizeTerms[] = AttributeTerm::updateOrCreate(
                ['attribute_id' => $sizeAttr->id, 'slug' => Str::slug($s)],
                ['name' => $s]
            );
        }

        // 3. Attach to a few products
        $products = Product::limit(5)->get();
        foreach ($products as $product) {
            // Attach a random color and size
            $product->attributes()->sync([
                $colorTerms[array_rand($colorTerms)]->id,
                $sizeTerms[array_rand($sizeTerms)]->id,
            ]);
            
            // Add a deal to some products
            if ($product->id % 2 == 0) {
                // We'll add deal fields in a migration soon, for now just updating basic fields
                $product->update([
                    'sale_price' => $product->price * 0.8,
                    'is_popular' => true
                ]);
            }
        }
    }
}

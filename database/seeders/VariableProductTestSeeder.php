<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\ProductVariation;
use App\Models\Attribute;
use App\Models\AttributeTerm;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class VariableProductTestSeeder extends Seeder
{
    public function run()
    {
        // 1. Create Weight Attribute
        $attr = Attribute::firstOrCreate(['slug' => 'weight'], [
            'name' => 'Weight',
            'type' => 'select'
        ]);

        // 2. Create Terms
        $t500 = AttributeTerm::firstOrCreate(['slug' => '500g', 'attribute_id' => $attr->id], ['name' => '500g']);
        $t1kg = AttributeTerm::firstOrCreate(['slug' => '1kg', 'attribute_id' => $attr->id], ['name' => '1kg']);

        // 3. Create Variable Product
        $product = Product::updateOrCreate(['slug' => 'soya-wadi-deluxe'], [
            'name' => 'Soya Wadi Masyeura Deluxe',
            'product_type' => 'variable',
            'status' => 'active',
            'price' => 10.00,
            'description' => 'Premium Soya Wadi available in multiple sizes.',
            'image' => 'https://nepstrading.com.au/wp-content/uploads/2018/12/20190219_144240-e1550552646408-scaled.jpg'
        ]);

        // 4. Create Variations
        ProductVariation::updateOrCreate(['product_id' => $product->id, 'sku' => 'SOYA-500G'], [
            'price' => 6.50,
            'stock_quantity' => 50,
            'stock_status' => 'instock',
            'attribute_combination' => ['Weight' => '500g']
        ]);

        ProductVariation::updateOrCreate(['product_id' => $product->id, 'sku' => 'SOYA-1KG'], [
            'price' => 12.00,
            'stock_quantity' => 30,
            'stock_status' => 'instock',
            'attribute_combination' => ['Weight' => '1kg']
        ]);
        
        $this->command->info('Variable Test Product Created: Soya Wadi Masyeura Deluxe');
    }
}

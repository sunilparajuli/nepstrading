<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;

class DemoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $electronics = Category::create(['name' => 'Electronics', 'slug' => 'electronics']);
        $clothing = Category::create(['name' => 'Clothing', 'slug' => 'clothing']);
        $computers = Category::create(['name' => 'Computers', 'slug' => 'computers', 'parent_id' => $electronics->id]);

        $laptop = Product::create([
            'name' => 'MacBook Pro M3',
            'slug' => 'macbook-pro-m3',
            'description' => 'Latest MacBook Pro with M3 chip',
            'short_description' => 'Fast and powerful.',
            'price' => 1999.99,
            'sale_price' => 1899.99,
            'sku' => 'MBP-M3',
            'stock_status' => 'instock',
            'status' => 'publish'
        ]);
        $laptop->categories()->attach($computers->id);

        $iphone = Product::create([
            'name' => 'iPhone 15 Pro',
            'slug' => 'iphone-15-pro',
            'description' => 'Titanium design, powerful camera.',
            'short_description' => 'The ultimate iPhone.',
            'price' => 999.99,
            'sku' => 'IP-15-PRO',
            'stock_status' => 'instock',
            'status' => 'publish'
        ]);
        $iphone->categories()->attach($electronics->id);

        $tshirt = Product::create([
            'name' => 'Premium Cotton T-Shirt',
            'slug' => 'premium-cotton-tshirt',
            'description' => 'Soft and comfortable 100% cotton.',
            'short_description' => 'Comfortable wear.',
            'price' => 29.99,
            'sku' => 'TS-PC',
            'stock_status' => 'instock',
            'status' => 'publish'
        ]);
        $tshirt->categories()->attach($clothing->id);
    }
}

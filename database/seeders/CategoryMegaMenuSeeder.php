<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategoryMegaMenuSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Fruits & Vegetables' => [
                'children' => ['Fresh Fruits', 'Fresh Vegetables', 'Herbs & Seasoning', 'Cuts & Sprouts', 'Exotics'],
                'image' => '/images/categories/fruits_veg.png'
            ],
            'Beverages' => [
                'children' => ['Soft Drinks', 'Coffee & Tea', 'Juices', 'Beer & Wine', 'Water & Tonic', 'Energy Drinks'],
                'image' => '/images/categories/beverages.png'
            ],
            'Dairy & Eggs' => [
                'children' => ['Milk & Cream', 'Cheese', 'Eggs', 'Butter & Fats', 'Yogurt & Curd'],
                'image' => '/images/categories/dairy_eggs.png'
            ],
            'Pantry' => [
                'children' => ['Grains & Rice', 'Oils & Vinegars', 'Spices', 'Pasta & Noodles', 'Baking Essentials', 'Canned Goods'],
                'image' => '/images/categories/pantry.png'
            ],
            'Bakery & Bread' => [
                'children' => ['Bread', 'Pastries', 'Cakes', 'Cookies & Biscuits', 'Gourmet Breads'],
                'image' => '/images/categories/bakery.png'
            ],
            'Meat & Seafood' => [
                'children' => ['Beef', 'Chicken', 'Fish', 'Prawns & Shellfish', 'Lamb & Mutton'],
                'image' => '/images/categories/meat_seafood.png'
            ],
            'Snacks & Munchies' => [
                'children' => ['Chips & Crisps', 'Chocolates', 'Biscuits', 'Popcorn', 'Nuts & Seeds'],
                'image' => 'https://images.unsplash.com/photo-1599490659223-e1539e76926a?auto=format&fit=crop&w=800&q=80'
            ],
            'Baby Care' => [
                'children' => ['Diapers', 'Baby Food', 'Baby Wipes', 'Baby Bath', 'Baby Lotion'],
                'image' => 'https://images.unsplash.com/photo-1555252333-978fead06c8d?auto=format&fit=crop&w=800&q=80'
            ],
            'Health & Wellness' => [
                'children' => ['Vitamins', 'First Aid', 'Oral Care', 'Hand Sanitizer', 'Masks'],
                'image' => 'https://images.unsplash.com/photo-1584308666744-24d5c474f2ae?auto=format&fit=crop&w=800&q=80'
            ],
            'Home & Kitchen' => [
                'children' => ['Detergents', 'Kitchen Rolls', 'Dishwash', 'Air Freshners', 'Pooja Needs'],
                'image' => 'https://images.unsplash.com/photo-1556911220-e150213ff167?auto=format&fit=crop&w=800&q=80'
            ],
            'Beauty & Grooming' => [
                'children' => ['Skin Care', 'Hair Care', 'Fragrances', 'Make Up', 'Bath & Handwash'],
                'image' => 'https://images.unsplash.com/photo-1596462502278-27bfaf433b03?auto=format&fit=crop&w=800&q=80'
            ]
        ];

        foreach ($categories as $parentName => $data) {
            $parent = Category::updateOrCreate(
                ['name' => $parentName],
                [
                    'slug' => str()->slug($parentName),
                    'image' => $data['image']
                ]
            );

            foreach ($data['children'] as $childName) {
                Category::updateOrCreate(
                    ['name' => $childName, 'parent_id' => $parent->id],
                    ['slug' => str()->slug($childName)]
                );
            }
        }
    }
}

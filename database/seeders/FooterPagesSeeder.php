<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class FooterPagesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            // Quick Links
            ['title' => 'About Us', 'footer_section' => 'Quick Links', 'sort_order' => 1],
            ['title' => 'Delivery Info', 'footer_section' => 'Quick Links', 'sort_order' => 2],
            ['title' => 'Contact', 'footer_section' => 'Quick Links', 'sort_order' => 3],
            
            // Customer Service
            ['title' => 'Returns Policy', 'footer_section' => 'Customer Service', 'sort_order' => 1],
            ['title' => 'FAQ', 'footer_section' => 'Customer Service', 'sort_order' => 2],
            ['title' => 'Terms & Conditions', 'footer_section' => 'Customer Service', 'sort_order' => 3],
        ];

        foreach ($pages as $p) {
            \App\Models\Page::updateOrCreate(
                ['slug' => \Illuminate\Support\Str::slug($p['title'])],
                [
                    'title' => $p['title'],
                    'content' => 'Content for ' . $p['title'],
                    'status' => 'published',
                    'footer_section' => $p['footer_section'],
                    'sort_order' => $p['sort_order'],
                    'show_on_footer' => true,
                ]
            );
        }
    }
}

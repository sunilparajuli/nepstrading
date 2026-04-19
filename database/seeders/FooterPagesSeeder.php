<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class FooterPagesSeeder extends Seeder
{
    public function run()
    {
        $pages = [
            [
                'title' => 'About Us',
                'slug' => 'about-us',
                'content' => '<h3>Our Story</h3><p>Nepstrading is your premier destination for authentic Indian and Nepali groceries. Founded with a passion for bringing the flavors of home to your doorstep, we pride ourselves on sourcing the highest quality spices, fresh produce, and daily essentials.</p><p>We serve thousands of happy customers across Australia, ensuring that the rich culinary heritage of South Asia is accessible to everyone.</p>',
                'status' => 'published',
                'footer_section' => 'Useful Links'
            ],
            [
                'title' => 'Contact Us',
                'slug' => 'contact',
                'content' => '<h3>Get in Touch</h3><p>Have questions about your order or our products? Our team is here to help!</p><p><strong>Email:</strong> info@nepstrading.com.au</p><p><strong>Phone:</strong> +61 4XX XXX XXX</p><p><strong>Address:</strong> 123 Market St, Sydney NSW 2000</p><p>Operating Hours: Monday - Saturday, 9:00 AM - 6:00 PM</p>',
                'status' => 'published',
                'footer_section' => 'Useful Links'
            ],
            [
                'title' => 'Shipping Info',
                'slug' => 'delivery-info',
                'content' => '<h3>Fast & Reliable Delivery</h3><p>We offer reliable shipping across Australia. Our delivery times vary by location:</p><ul><li><strong>Sydney Metro:</strong> 1-2 Business Days</li><li><strong>Other Major Cities:</strong> 3-5 Business Days</li><li><strong>Regional Areas:</strong> 5-7 Business Days</li></ul><p>Free shipping is available for orders over $69 in select areas.</p>',
                'status' => 'published',
                'footer_section' => 'Useful Links'
            ],
            [
                'title' => 'Returns Policy',
                'slug' => 'returns-policy',
                'content' => '<h3>Easy Returns</h3><p>We want you to be completely satisfied with your purchase. If a product is damaged or not as described, you can return it within 7 days of delivery.</p><p>Please note that perishable items (fresh produce) must be reported within 24 hours of delivery for a replacement or refund.</p>',
                'status' => 'published',
                'footer_section' => 'Useful Links'
            ],
            [
                'title' => 'Privacy Policy',
                'slug' => 'privacy-policy',
                'content' => '<h3>Privacy Policy</h3><p>Your privacy is important to us. This policy explains how we collect, use, and protect your personal information.</p><h4>Information Collection</h4><p>We collect information when you register, place an order, or subscribe to our newsletter. This includes your name, email, and shipping address.</p><h4>Data Usage</h4><p>Your data is used solely to process orders and improve our service. We do not sell your information to third parties.</p><h4>Mobile App Compliance</h4><p>Our mobile application requests necessary permissions for location (to calculate shipping) and notifications (for order updates). You can manage these in your settings.</p>',
                'status' => 'published',
                'footer_section' => 'Useful Links'
            ]
        ];

        foreach ($pages as $p) {
            Page::updateOrCreate(['slug' => $p['slug']], $p);
        }

        $this->command->info('Footer pages seeded successfully!');
    }
}

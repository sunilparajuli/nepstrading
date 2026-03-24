<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class OrderSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::firstOrCreate(
            ['email' => 'admin@example.com'],
            [
                'name' => 'Admin User',
                'password' => Hash::make('password'),
            ]
        );

        $products = Product::all();
        if ($products->isEmpty()) return;

        for ($i = 0; $i < 15; $i++) {
            $order = Order::create([
                'user_id' => $user->id,
                'status' => ['processing', 'completed', 'on-hold', 'pending'][rand(0, 3)],
                'subtotal' => 0,
                'total' => 0,
                'shipping_total' => 0,
                'tax_total' => 0,
                'currency' => 'USD',
            ]);

            $orderTotal = 0;
            for ($j = 0; $j < rand(1, 3); $j++) {
                $product = $products->random();
                $qty = rand(1, 2);
                $price = $product->sale_price ?? $product->price;
                $itemTotal = $price * $qty;
                
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'price' => $price,
                    'total' => $itemTotal,
                ]);
                
                $orderTotal += $itemTotal;
            }
            
            $order->update([
                'subtotal' => $orderTotal,
                'total' => $orderTotal
            ]);
        }
    }
}

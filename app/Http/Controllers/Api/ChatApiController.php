<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SiteSetting;
use Illuminate\Support\Facades\Log;

class ChatApiController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = trim($request->message);

        try {
            // 1. Database Search (Optimized for accuracy)
            // Split message into keywords and remove filler words
            $stopWords = ['i', 'want', 'to', 'buy', 'find', 'me', 'some', 'is', 'available', 'the', 'a', 'an', 'please', 'can', 'you', 'show'];
            $keywords = collect(explode(' ', strtolower($userMessage)))
                ->filter(fn($w) => strlen($w) > 1 && !in_array($w, $stopWords))
                ->values();

            $query = Product::whereIn('status', ['active', 'publish']);
            
            if ($keywords->isNotEmpty()) {
                $query->where(function($q) use ($keywords) {
                    foreach ($keywords as $word) {
                        $q->orWhere('name', 'like', "%{$word}%")
                          ->orWhere('description', 'like', "%{$word}%");
                    }
                });
            } else {
                // Fallback to strict match if no quality keywords found
                $query->where('name', 'like', "%{$userMessage}%");
            }

            $products = $query->limit(15)->get(['id', 'name', 'slug', 'product_type']);

            $categories = Category::where('name', 'like', "%{$userMessage}%")
                ->limit(5)
                ->get(['id', 'name', 'slug']);

            // 2. Build Response Narrative
            $count = $products->count();
            $message = $count > 0 
                ? "We found {$count} products matching your inquiry." 
                : "I couldn't find any products matching '{$userMessage}'. Please try another keyword or contact us.";

            // 3. Construct Actions
            $actions = [];

            // Add Categories first if found
            foreach ($categories as $cat) {
                $actions[] = [
                    'type' => 'category',
                    'slug' => $cat->slug,
                    'name' => "Explore {$cat->name}"
                ];
            }

            // Add Products
            foreach ($products as $p) {
                $actions[] = [
                    'type' => 'product',
                    'id' => $p->id,
                    'slug' => $p->slug,
                    'name' => $p->name,
                    'is_variable' => $p->product_type === 'variable'
                ];
            }

            // Add Contact if no products
            if ($count === 0) {
                $actions[] = [
                    'type' => 'contact',
                    'phone' => SiteSetting::getValue('footer_phone', '+61 4XX XXX XXX')
                ];
            }

            return response()->json([
                'message' => $message,
                'actions' => $actions,
            ]);

        } catch (\Exception $e) {
            Log::error('Chat Search Error: ' . $e->getMessage());
            return response()->json([
                'message' => "I'm sorry, I'm having trouble searching our store right now. Please try again or contact us.",
                'actions' => [['type' => 'contact', 'phone' => SiteSetting::getValue('footer_phone')]],
            ], 500);
        }
    }
}

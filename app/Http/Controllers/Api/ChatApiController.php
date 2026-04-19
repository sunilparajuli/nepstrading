<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\SiteSetting;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Log;

class ChatApiController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:1000',
        ]);

        $userMessage = $request->message;

        try {
            // 1. Get Context: Search for relevant products based on query
            $products = Product::where('name', 'like', "%{$userMessage}%")
                ->orWhere('description', 'like', "%{$userMessage}%")
                ->limit(5)
                ->get(['id', 'name', 'price', 'slug']);

            $categories = Category::where('name', 'like', "%{$userMessage}%")
                ->limit(3)
                ->get(['id', 'name', 'slug']);

            $contactPhone = SiteSetting::getValue('footer_phone', '+61 000 000 000');
            $contactEmail = SiteSetting::getValue('footer_email', 'info@nepstrading.com.au');

            // 2. Load Global Catalog Context (Cached for performance)
            $catalog = \Illuminate\Support\Facades\Cache::remember('ai_product_catalog', 3600, function () {
                return \Illuminate\Support\Facades\Storage::disk('local')->exists('catalog.json') 
                    ? \Illuminate\Support\Facades\Storage::disk('local')->get('catalog.json') 
                    : '[]';
            });

            $prompt = "You are a 'Zero-Fluff' Shop Assistant for Nepstrading.
            
            FULL STORE CATALOG (JSON):
            {$catalog}

            Rules:
            1. NEVER list product names in the text message.
            2. Narrative MUST ONLY be: 'We found [X] products matching your inquiry.' (where X is the number of results).
            3. Include all relevant [[PRODUCT:slug]] tags after the sentence.
            4. If no products found, say 'No products found. Contact store [[CONTACT]]'.
            5. Keep response to exactly one sentence plus tags.

            User Query: \"{$userMessage}\"";

            // 3. Call Gemini (Using gemini-flash-latest for best compatibility)
            $result = Gemini::generativeModel('gemini-flash-latest')->generateContent($prompt);
            $botResponse = $result->text();

            // 4. Parse Actions (Improved for multiple slugs/ids)
            $actions = [];
            
            // Extract Product Slugs
            if (preg_match_all('/\[\[PRODUCT:([^\]]+)\]\]/', $botResponse, $matches)) {
                $slugs = array_unique($matches[1]);
                $foundProducts = Product::whereIn('slug', $slugs)->get(['id', 'slug', 'name', 'product_type']);
                foreach ($foundProducts as $p) {
                    $actions[] = [
                        'type' => 'product', 
                        'id' => $p->id, 
                        'slug' => $p->slug,
                        'name' => $p->name,
                        'is_variable' => $p->product_type === 'variable'
                    ];
                }
            }

            // Extract Category Slugs
            if (preg_match_all('/\[\[CATEGORY:([^\]]+)\]\]/', $botResponse, $matches)) {
                foreach (array_unique($matches[1]) as $slug) {
                    $actions[] = ['type' => 'category', 'slug' => $slug];
                }
            }

            if (str_contains($botResponse, '[[CONTACT]]')) {
                $actions[] = ['type' => 'contact', 'phone' => $contactPhone];
            }

            // Clean up the text for display (remove tags)
            $cleanResponse = preg_replace('/\[\[.*?\]\]/', '', $botResponse);

            return response()->json([
                'message' => trim($cleanResponse),
                'actions' => $actions,
            ]);

        } catch (\Exception $e) {
            Log::error('ChatBot Error: ' . $e->getMessage());
            return response()->json([
                'message' => "I'm sorry, I'm having trouble connecting to my brain right now. Please try again or contact us at " . SiteSetting::getValue('footer_phone'),
                'actions' => [['type' => 'contact', 'phone' => SiteSetting::getValue('footer_phone')]],
            ], 500);
        }
    }
}

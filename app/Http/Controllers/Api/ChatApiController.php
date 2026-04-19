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
            // 1. Smart Filtering: Get top 100 most relevant items locally first
            // This prevents hitting Gemini token/rate limits with the full catalog
            $products = Product::with('categories')
                ->where('name', 'like', "%{$userMessage}%")
                ->orWhere('description', 'like', "%{$userMessage}%")
                ->limit(100)
                ->get();

            $relevantCatalog = $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'n' => $p->name,
                    'c' => $p->categories->first()?->name ?? 'General',
                    's' => $p->slug,
                    't' => $p->product_type,
                    'v' => $p->variations->count()
                ];
            })->values();

            $categories = Category::where('name', 'like', "%{$userMessage}%")
                ->limit(5)
                ->get(['id', 'name', 'slug']);

            $contactPhone = SiteSetting::getValue('footer_phone', '+61 4XX XXX XXX');

            $prompt = "You are a 'Zero-Fluff' Shop Assistant for Nepstrading.
            
            RELEVANT PRODUCTS (JSON):
            " . json_encode($relevantCatalog) . "

            Rules:
            1. NEVER list product names in the text message.
            2. Narrative MUST ONLY be: 'We found [X] productsmatching your inquiry.' (where X is the number of results).
            3. Include all relevant [[PRODUCT:slug]] tags after the sentence.
            4. If no products found, say 'No products found matching your search. Contact store [[CONTACT]]'.
            5. Keep response to exactly one sentence plus tags.

            User Query: \"{$userMessage}\"";

            // 2. Call Gemini (Using gemini-flash-latest for best speed/cost)
            $result = Gemini::generativeModel('gemini-1.5-flash')->generateContent($prompt);
            $botResponse = $result->text();

            // 3. Parse Actions
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

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

            // 2. Load Global Catalog Context (Using Laravel Storage for correct path resolution)
            $catalog = \Illuminate\Support\Facades\Storage::disk('local')->exists('catalog.json') 
                ? \Illuminate\Support\Facades\Storage::disk('local')->get('catalog.json') 
                : '[]';

            $prompt = "You are a helpful Shop Assistant for Nepstrading.
            
            FULL STORE CATALOG (JSON):
            {$catalog}

            Rules:
            1. Use the catalog above as your absolute source of truth for products.
            2. If the user asks for something, perform fuzzy matching (e.g. 'Soya' -> 'Soya Wadi').
            3. Responses MUST be concise (max 2 sentences).
            4. Include [[PRODUCT:id]] for recommended products.
            5. Include [[CATEGORY:slug]] for category suggestions.
            6. If not found in catalog, suggest similar or give contact [[CONTACT]].

            User Query: \"{$userMessage}\"";

            // 3. Call Gemini (Using gemini-flash-latest for best compatibility)
            $result = Gemini::generativeModel('gemini-flash-latest')->generateContent($prompt);
            $botResponse = $result->text();

            // 4. Parse Actions (simple parsing for the mobile app)
            $actions = [];
            if (preg_match('/\[\[PRODUCT:(\d+)\]\]/', $botResponse, $matches)) {
                $actions[] = ['type' => 'product', 'id' => (int)$matches[1]];
            }
            if (preg_match('/\[\[CATEGORY:([^\]]+)\]\]/', $botResponse, $matches)) {
                $actions[] = ['type' => 'category', 'slug' => $matches[1]];
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

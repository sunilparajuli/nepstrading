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

            $prompt = "You are a 'Zero-Fluff' Shop Assistant for Nepstrading.
            
            FULL STORE CATALOG (JSON):
            {$catalog}

            Rules:
            1. NEVER use introductory paragraphs or filler text (e.g. 'We have several options').
            2. If products are found, ONLY list their names followed by the tag.
            3. Example Response: 'Soya Wadi [[PRODUCT:soya-wadi]] \n Soya Bean [[PRODUCT:soya-bean]]'
            4. Keep text at an absolute minimum.
            5. Use [[PRODUCT:slug]], [[CATEGORY:slug]], or [[CONTACT]].

            User Query: \"{$userMessage}\"";

            // 3. Call Gemini (Using gemini-flash-latest for best compatibility)
            $result = Gemini::generativeModel('gemini-flash-latest')->generateContent($prompt);
            $botResponse = $result->text();

            // 4. Parse Actions (Improved for multiple slugs/ids)
            $actions = [];
            
            // Extract Product Slugs
            if (preg_match_all('/\[\[PRODUCT:([^\]]+)\]\]/', $botResponse, $matches)) {
                $slugs = array_unique($matches[1]);
                $foundProducts = Product::whereIn('slug', $slugs)->get(['id', 'slug', 'name']);
                foreach ($foundProducts as $p) {
                    $actions[] = [
                        'type' => 'product', 
                        'id' => $p->id, 
                        'slug' => $p->slug,
                        'name' => $p->name
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

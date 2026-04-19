<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class ExportProductCatalog extends Command
{
    protected $signature = 'products:export-catalog';
    protected $description = 'Export products to a JSON file for the AI Shop Assistant context';

    public function handle()
    {
        $this->info('Starting catalog export...');

        $products = Product::with('category')
            ->where('status', 'publish')
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'n' => $product->name, // Minified key
                    'c' => $product->category ? $product->category->name : 'General', // Minified key
                    's' => $product->slug // Needed for links
                ];
            });

        $path = 'catalog.json';
        Storage::disk('local')->put($path, json_encode($products));

        $this->info("Successfully exported " . $products->count() . " products to storage/app/{$path}");
    }
}

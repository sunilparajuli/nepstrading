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

        $products = Product::with('categories')
            ->whereIn('status', ['active', 'publish'])
            ->get()
            ->map(function ($product) {
                $category = $product->categories->first();
                return [
                    'id' => $product->id,
                    'n' => $product->name,
                    'c' => $category ? $category->name : 'General',
                    's' => $product->slug
                ];
            });

        $path = 'catalog.json';
        Storage::disk('local')->put($path, json_encode($products));

        $this->info("Successfully exported " . $products->count() . " products to storage/app/{$path}");
    }
}

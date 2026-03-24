<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Support\Str;

class MigrateWordPress extends Command
{
    protected $signature = 'migrate:wordpress {--limit=100 : Maximum number of products to migrate}';
    protected $description = 'Migrate products and categories from WooCommerce/WordPress';

    protected $baseUrl;
    protected $consumerKey;
    protected $consumerSecret;

    public function handle()
    {
        $this->baseUrl = env('WORDPRESS_URL');
        $this->consumerKey = env('WOO_CONSUMER_KEY');
        $this->consumerSecret = env('WOO_CONSUMER_SECRET');

        if (!$this->baseUrl || !$this->consumerKey || !$this->consumerSecret) {
            $this->error('Please configure WORDPRESS_URL, WOO_CONSUMER_KEY, and WOO_CONSUMER_SECRET in .env');
            return;
        }

        $this->info('Starting migration...');

        // 1. Sync Categories
        $this->syncCategories();

        // 2. Sync Products
        $this->syncProducts();

        $this->info('Migration completed successfully!');
    }

    protected function syncCategories()
    {
        $this->info('Syncing categories...');
        
        $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
            ->get("{$this->baseUrl}/wp-json/wc/v3/products/categories", ['per_page' => 100]);

        if ($response->failed()) {
            $this->error('Failed to fetch categories: ' . $response->body());
            return;
        }

        $categories = $response->json();
        
        // Sort by parent so we create parents first
        usort($categories, fn($a, $b) => $a['parent'] <=> $b['parent']);

        foreach ($categories as $wpCat) {
            $parentId = null;
            if ($wpCat['parent'] > 0) {
                // Find local parent
                $parentNameResponse = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                    ->get("{$this->baseUrl}/wp-json/wc/v3/products/categories/{$wpCat['parent']}");
                
                if ($parentNameResponse->successful()) {
                    $parentWp = $parentNameResponse->json();
                    $localParent = Category::where('slug', $parentWp['slug'])->first();
                    $parentId = $localParent?->id;
                }
            }

            Category::updateOrCreate(
                ['slug' => $wpCat['slug']],
                [
                    'name' => $wpCat['name'],
                    'parent_id' => $parentId,
                    'image' => $wpCat['image']['src'] ?? null,
                ]
            );
        }

        $this->info('Categories synced.');
    }

    protected function syncProducts()
    {
        $limit = $this->option('limit');
        $this->info("Syncing up to {$limit} products...");

        $page = 1;
        $totalSynced = 0;

        while ($totalSynced < $limit) {
            $response = Http::withBasicAuth($this->consumerKey, $this->consumerSecret)
                ->get("{$this->baseUrl}/wp-json/wc/v3/products", [
                    'per_page' => 20,
                    'page' => $page,
                ]);

            if ($response->failed() || empty($response->json())) {
                break;
            }

            $products = $response->json();

            foreach ($products as $wpProd) {
                $product = Product::updateOrCreate(
                    ['sku' => $wpProd['sku'] ?: 'WP-' . $wpProd['id']],
                    [
                        'name' => $wpProd['name'],
                        'slug' => $wpProd['slug'] ?: Str::slug($wpProd['name']),
                        'description' => $wpProd['description'],
                        'short_description' => $wpProd['short_description'],
                        'price' => $wpProd['regular_price'] ?: 0,
                        'sale_price' => $wpProd['sale_price'] ?: null,
                        'stock_status' => $wpProd['stock_status'] === 'instock' ? 'instock' : 'outofstock',
                        'manage_stock' => $wpProd['manage_stock'],
                        'stock_quantity' => $wpProd['stock_quantity'] ?: 0,
                        'status' => 'published',
                        'product_type' => 'simple', // defaulting for now
                    ]
                );

                // Sync Categories
                $catIds = [];
                foreach ($wpProd['categories'] as $wpCat) {
                    $localCat = Category::where('slug', $wpCat['slug'])->first();
                    if ($localCat) {
                        $catIds[] = $localCat->id;
                    }
                }
                $product->categories()->sync($catIds);

                // Sync Images
                $this->syncProductImages($product, $wpProd['images']);

                $totalSynced++;
                if ($totalSynced >= $limit) break;
            }

            $page++;
            $this->info("Processed page {$page}...");
        }
    }

    protected function syncProductImages($product, $images)
    {
        // First image as main image
        if (!empty($images)) {
            $mainImgUrl = $images[0]['src'];
            $localMainPath = $this->downloadImage($mainImgUrl, 'products');
            $product->update(['image' => $localMainPath]);

            // Clear old gallery and add new ones
            $product->images()->delete();
            foreach ($images as $index => $imgData) {
                $localPath = $this->downloadImage($imgData['src'], 'products/gallery');
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $localPath,
                    'sort_order' => $index,
                ]);
            }
        }
    }

    protected function downloadImage($url, $folder)
    {
        try {
            $name = basename(parse_url($url, PHP_URL_PATH));
            $path = $folder . '/' . time() . '_' . $name;
            
            $content = file_get_contents($url);
            if ($content) {
                Storage::disk('public')->put($path, $content);
                return 'storage/' . $path;
            }
        } catch (\Exception $e) {
            $this->warn("Failed to download image: {$url}");
        }
        
        return $url; // fall back to original URL
    }
}

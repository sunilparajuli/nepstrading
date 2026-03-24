<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;

class MigrateWordPressData extends Command
{
    protected $signature = 'app:migrate-wp';
    protected $description = 'Migrate products and categories from WordPress to Laravel';

    public function handle()
    {
        $this->info('Starting migration...');

        // 1. Migrate Categories
        $wpCategories = DB::table('wp_term_taxonomy')
            ->join('wp_terms', 'wp_term_taxonomy.term_id', '=', 'wp_terms.term_id')
            ->where('taxonomy', 'product_cat')
            ->select('wp_terms.term_id', 'wp_terms.name', 'wp_terms.slug', 'wp_term_taxonomy.parent')
            ->get();

        $categoryMap = [];

        foreach ($wpCategories as $wpCat) {
            $category = Category::updateOrCreate(
                ['slug' => $wpCat->slug],
                [
                    'name' => $wpCat->name,
                    'parent_id' => 0,
                ]
            );
            $categoryMap[$wpCat->term_id] = $category->id;
        }

        // Update Parents
        foreach ($wpCategories as $wpCat) {
            if ($wpCat->parent > 0 && isset($categoryMap[$wpCat->parent])) {
                Category::where('id', $categoryMap[$wpCat->term_id])
                    ->update(['parent_id' => $categoryMap[$wpCat->parent]]);
            }
        }

        $this->info('Categories migrated: ' . count($wpCategories));

        // 2. Migrate Products
        $wpProducts = DB::table('wp_posts')
            ->where('post_type', 'product')
            ->where('post_status', 'publish')
            ->get();

        $bar = $this->output->createProgressBar(count($wpProducts));
        $bar->start();

        foreach ($wpProducts as $wpProd) {
            $meta = DB::table('wp_postmeta')->where('post_id', $wpProd->ID)->pluck('meta_value', 'meta_key');

            $price = $meta['_regular_price'] ?? $meta['_price'] ?? 0;
            $salePrice = $meta['_sale_price'] ?? null;
            $sku = $meta['_sku'] ?? null;
            $stock = $meta['_stock'] ?? 0;
            $manageStock = ($meta['_manage_stock'] ?? 'no') === 'yes';

            // Get Image
            $imagePath = null;
            $thumbnailId = $meta['_thumbnail_id'] ?? null;
            if ($thumbnailId) {
                $attachmentMeta = DB::table('wp_postmeta')
                    ->where('post_id', $thumbnailId)
                    ->where('meta_key', '_wp_attached_file')
                    ->first();
                if ($attachmentMeta) {
                    $imagePath = '/storage/wp-uploads/' . $attachmentMeta->meta_value;
                }
            }

            $product = Product::updateOrCreate(
                ['slug' => $wpProd->post_name ?: Str::slug($wpProd->post_title)],
                [
                    'name' => $wpProd->post_title,
                    'description' => $wpProd->post_content,
                    'short_description' => $wpProd->post_excerpt,
                    'price' => (float)$price,
                    'sale_price' => ($salePrice && $salePrice != '') ? (float)$salePrice : null,
                    'sku' => $sku,
                    'stock_status' => (int)$stock > 0 ? 'instock' : 'outofstock',
                    'manage_stock' => $manageStock,
                    'stock_quantity' => (int)$stock,
                    'status' => 'active',
                    'image' => $imagePath,
                ]
            );

            // Link Categories
            $wpTermRelationships = DB::table('wp_term_relationships')
                ->where('object_id', $wpProd->ID)
                ->pluck('term_taxonomy_id');

            $categoryIds = [];
            foreach ($wpTermRelationships as $termTaxId) {
                $termId = DB::table('wp_term_taxonomy')->where('term_taxonomy_id', $termTaxId)->value('term_id');
                if (isset($categoryMap[$termId])) {
                    $categoryIds[] = $categoryMap[$termId];
                }
            }
            $product->categories()->sync($categoryIds);
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nProducts migrated: " . count($wpProducts));
        $this->info('Migration completed successfully!');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    public function getRouteKeyName()
    {
        return 'slug';
    }

    protected $fillable = [
        'name', 'slug', 'description', 'short_description',
        'price', 'sale_price', 'sku', 'stock_status',
        'manage_stock', 'stock_quantity',
        'weight', 'dimensions', 'status', 'image',
        'is_seasonal', 'is_popular', 'product_type',
        'deal_ends_at', 'deal_total_stock', 'deal_sales_count',
        'allow_add_to_cart', 'cart_disabled_message',
        'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image'
    ];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'product_category');
    }

    public function attributes()
    {
        return $this->belongsToMany(AttributeTerm::class, 'product_attributes');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class)->where('status', 'approved');
    }

    protected function casts(): array
    {
        return [
            'deal_ends_at' => 'datetime',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
            'allow_add_to_cart' => 'boolean',
        ];
    }

    public function variations()
    {
        return $this->hasMany(ProductVariation::class);
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function getAverageRatingAttribute(): float
    {
        return round($this->approvedReviews()->avg('rating') ?? 0, 1);
    }

    public function getReviewCountAttribute(): int
    {
        return $this->approvedReviews()->count();
    }

    public function getIsOnDealAttribute(): bool
    {
        return $this->deal_ends_at && $this->deal_ends_at->isFuture();
    }

    public function getDealProgressAttribute(): int
    {
        if (!$this->deal_total_stock) return 0;
        return min(100, round(($this->deal_sales_count / $this->deal_total_stock) * 100));
    }
}

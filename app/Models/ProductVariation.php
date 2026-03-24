<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductVariation extends Model
{
    protected $fillable = [
        'product_id', 'sku', 'price', 'sale_price',
        'stock_quantity', 'stock_status', 'image', 'attribute_combination',
    ];

    protected function casts(): array
    {
        return [
            'attribute_combination' => 'array',
            'price' => 'decimal:2',
            'sale_price' => 'decimal:2',
        ];
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}

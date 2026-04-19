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

    public function getFormattedAttributesAttribute()
    {
        if (!$this->attribute_combination || !is_array($this->attribute_combination)) return '';
        
        $parts = [];
        foreach ($this->attribute_combination as $key => $value) {
            if (is_numeric($key)) {
                $parts[] = $value;
            } else {
                $parts[] = "$key: $value";
            }
        }
        return implode(', ', $parts);
    }
}

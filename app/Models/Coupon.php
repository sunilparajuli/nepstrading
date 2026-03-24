<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $fillable = [
        'code', 'type', 'amount', 'min_order', 'max_uses', 'usage_count',
        'per_user_limit', 'applies_to', 'product_ids', 'category_ids',
        'free_shipping', 'is_active', 'starts_at', 'expires_at',
    ];

    protected function casts(): array
    {
        return [
            'product_ids' => 'array',
            'category_ids' => 'array',
            'free_shipping' => 'boolean',
            'is_active' => 'boolean',
            'starts_at' => 'datetime',
            'expires_at' => 'datetime',
            'amount' => 'decimal:2',
            'min_order' => 'decimal:2',
        ];
    }

    public function isValid(float $subtotal = 0): bool
    {
        if (!$this->is_active) return false;
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->max_uses && $this->usage_count >= $this->max_uses) return false;
        if ($this->min_order && $subtotal < $this->min_order) return false;

        return true;
    }

    public function calculateDiscount(float $subtotal): float
    {
        if ($this->type === 'percentage') {
            return round($subtotal * ($this->amount / 100), 2);
        }

        return min($this->amount, $subtotal);
    }
}

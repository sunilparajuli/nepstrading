<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'user_id', 'status', 'subtotal', 'total',
        'shipping_total', 'shipping_name', 'tax_total', 'tax_name', 'currency',
        'coupon_code', 'discount_total',
        'billing_first_name', 'billing_last_name', 'billing_address',
        'billing_city', 'billing_postcode', 'billing_phone', 'billing_email',
        'shipping_first_name', 'shipping_last_name', 'shipping_address',
        'shipping_city', 'shipping_postcode', 'order_notes'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}

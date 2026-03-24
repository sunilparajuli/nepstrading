<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLocation extends Model
{
    protected $fillable = ['shipping_zone_id', 'type', 'code'];

    public function zone()
    {
        return $this->belongsTo(ShippingZone::class, 'shipping_zone_id');
    }
}

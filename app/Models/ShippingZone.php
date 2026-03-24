<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingZone extends Model
{
    protected $fillable = ['name', 'is_enabled'];

    public function locations()
    {
        return $this->hasMany(ShippingLocation::class);
    }

    public function rates()
    {
        return $this->hasMany(ShippingRate::class);
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttributeTerm extends Model
{
    protected $fillable = ['attribute_id', 'name', 'slug', 'value', 'weight'];

    public function attribute()
    {
        return $this->belongsTo(Attribute::class);
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes');
    }
}

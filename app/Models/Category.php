<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    protected $fillable = [
        'name', 'slug', 'parent_id', 'image',
        'meta_title', 'meta_description', 'meta_keywords',
        'og_title', 'og_description', 'og_image'
    ];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function scopeWithChildren($query)
    {
        return $query->with('children');
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_category');
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    protected $fillable = ['name', 'type', 'code', 'parent_id', 'is_enabled'];

    public function parent()
    {
        return $this->belongsTo(Location::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Location::class, 'parent_id');
    }

    public function scopeStates($query)
    {
        return $query->where('type', 'state');
    }

    public function scopeCities($query)
    {
        return $query->where('type', 'city');
    }
}

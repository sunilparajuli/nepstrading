<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $fillable = ['name', 'slug', 'type', 'weight'];

    public function terms()
    {
        return $this->hasMany(AttributeTerm::class);
    }
}

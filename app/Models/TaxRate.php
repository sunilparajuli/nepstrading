<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    protected $fillable = [
        'name', 'country', 'state', 'rate', 'priority', 'compound', 'shipping', 'is_active',
    ];

    protected function casts(): array
    {
        return [
            'rate' => 'decimal:2',
            'compound' => 'boolean',
            'shipping' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    public static function getRate(string $state = null, string $country = 'AU'): ?self
    {
        return static::where('is_active', true)
            ->where('country', $country)
            ->where(function ($q) use ($state) {
                $q->where('state', $state)->orWhereNull('state');
            })
            ->orderByRaw('state IS NULL ASC')
            ->orderBy('priority')
            ->first();
    }
}

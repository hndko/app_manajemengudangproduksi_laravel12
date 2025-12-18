<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PriceType extends Model
{
    protected $fillable = [
        'name',
        'description',
        'discount_percentage',
        'is_active',
    ];

    protected $casts = [
        'discount_percentage' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

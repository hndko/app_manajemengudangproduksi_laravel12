<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function materials(): HasMany
    {
        return $this->hasMany(Material::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'material' ? 'Material' : 'Produk';
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeMaterials($query)
    {
        return $query->where('type', 'material');
    }

    public function scopeProducts($query)
    {
        return $query->where('type', 'produk');
    }
}

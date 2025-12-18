<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Product extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'unit_id',
        'description',
        'image',
        'base_price',
        'selling_price',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'base_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'minimum_stock' => 'integer',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = self::generateCode();
            }
        });
    }

    public static function generateCode(): string
    {
        $prefix = 'PRD-';
        $last = self::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        $number = $last ? (int) substr($last->code, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function stocks(): MorphMany
    {
        return $this->morphMany(Stock::class, 'stockable');
    }

    public function prices(): HasMany
    {
        return $this->hasMany(ProductPrice::class);
    }

    public function getTotalStockAttribute(): int
    {
        return $this->stocks->sum('quantity');
    }

    public function getStockInWarehouse(int $warehouseId): int
    {
        return $this->stocks()
            ->where('warehouse_id', $warehouseId)
            ->value('quantity') ?? 0;
    }

    public function getPriceForType(int $priceTypeId): float
    {
        $price = $this->prices()
            ->where('price_type_id', $priceTypeId)
            ->first();

        return $price ? (float) $price->price : (float) $this->selling_price;
    }

    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->minimum_stock;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function getProfitMarginAttribute(): float
    {
        if ($this->base_price <= 0) {
            return 0;
        }

        return (($this->selling_price - $this->base_price) / $this->base_price) * 100;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

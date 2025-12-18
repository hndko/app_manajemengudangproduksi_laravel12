<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Material extends Model
{
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'unit_id',
        'description',
        'image',
        'purchase_price',
        'minimum_stock',
        'is_active',
    ];

    protected $casts = [
        'purchase_price' => 'decimal:2',
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
        $prefix = 'MAT-';
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

    public function getIsLowStockAttribute(): bool
    {
        return $this->total_stock <= $this->minimum_stock;
    }

    public function getImageUrlAttribute(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeLowStock($query)
    {
        return $query->whereHas('stocks', function ($q) {
            $q->havingRaw('SUM(quantity) <= materials.minimum_stock');
        });
    }
}

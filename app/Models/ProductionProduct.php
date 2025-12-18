<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionProduct extends Model
{
    protected $fillable = [
        'production_id',
        'product_id',
        'warehouse_id',
        'quantity_planned',
        'quantity_produced',
        'quantity_rejected',
        'unit_cost',
        'notes',
    ];

    protected $casts = [
        'quantity_planned' => 'integer',
        'quantity_produced' => 'integer',
        'quantity_rejected' => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getQuantityGoodAttribute(): int
    {
        return $this->quantity_produced - $this->quantity_rejected;
    }

    public function getYieldRateAttribute(): float
    {
        if ($this->quantity_planned <= 0) {
            return 0;
        }

        return ($this->quantity_good / $this->quantity_planned) * 100;
    }

    public function getTotalValueAttribute(): float
    {
        return $this->quantity_good * $this->unit_cost;
    }
}

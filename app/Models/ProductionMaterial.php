<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductionMaterial extends Model
{
    protected $fillable = [
        'production_id',
        'material_id',
        'warehouse_id',
        'quantity_planned',
        'quantity_used',
        'unit_cost',
        'notes',
    ];

    protected $casts = [
        'quantity_planned' => 'integer',
        'quantity_used' => 'integer',
        'unit_cost' => 'decimal:2',
    ];

    public function production(): BelongsTo
    {
        return $this->belongsTo(Production::class);
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(Material::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function getTotalCostAttribute(): float
    {
        return $this->quantity_used * $this->unit_cost;
    }
}

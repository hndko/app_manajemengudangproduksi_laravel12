<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ReturnItem extends Model
{
    protected $fillable = [
        'return_id',
        'product_id',
        'quantity',
        'condition',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
    ];

    public function return(): BelongsTo
    {
        return $this->belongsTo(Returns::class, 'return_id');
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getConditionLabelAttribute(): string
    {
        return match($this->condition) {
            'baik' => 'Baik',
            'rusak' => 'Rusak',
            'cacat' => 'Cacat',
            default => $this->condition,
        };
    }

    public function getConditionColorAttribute(): string
    {
        return match($this->condition) {
            'baik' => 'success',
            'rusak' => 'danger',
            'cacat' => 'warning',
            default => 'secondary',
        };
    }
}

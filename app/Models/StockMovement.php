<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockMovement extends Model
{
    protected $fillable = [
        'stock_id',
        'type',
        'quantity',
        'quantity_before',
        'quantity_after',
        'reference_type',
        'reference_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'quantity_before' => 'integer',
        'quantity_after' => 'integer',
    ];

    public function stock(): BelongsTo
    {
        return $this->belongsTo(Stock::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'in' => 'Masuk',
            'out' => 'Keluar',
            'adjustment' => 'Penyesuaian',
            'transfer' => 'Transfer',
            default => $this->type,
        };
    }

    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'in' => 'success',
            'out' => 'danger',
            'adjustment' => 'warning',
            'transfer' => 'info',
            default => 'secondary',
        };
    }
}

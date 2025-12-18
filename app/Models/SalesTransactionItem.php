<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesTransactionItem extends Model
{
    protected $fillable = [
        'sales_transaction_id',
        'product_id',
        'quantity',
        'unit_price',
        'discount_percentage',
        'discount_amount',
        'total_price',
        'notes',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'unit_price' => 'decimal:2',
        'discount_percentage' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'total_price' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            $subtotal = $model->quantity * $model->unit_price;

            if ($model->discount_percentage > 0) {
                $model->discount_amount = $subtotal * ($model->discount_percentage / 100);
            }

            $model->total_price = $subtotal - $model->discount_amount;
        });
    }

    public function salesTransaction(): BelongsTo
    {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->unit_price;
    }
}

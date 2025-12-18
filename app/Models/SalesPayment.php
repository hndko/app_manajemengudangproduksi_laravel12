<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SalesPayment extends Model
{
    protected $fillable = [
        'number',
        'sales_transaction_id',
        'date',
        'amount',
        'method',
        'reference',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
    ];

    public static function generateNumber(): string
    {
        $year = now()->format('Y');
        $prefix = "PAY-{$year}-";

        $last = self::where('number', 'like', $prefix . '%')
            ->orderBy('number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function salesTransaction(): BelongsTo
    {
        return $this->belongsTo(SalesTransaction::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getMethodLabelAttribute(): string
    {
        return match($this->method) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'giro' => 'Giro',
            'other' => 'Lainnya',
            default => $this->method,
        };
    }
}

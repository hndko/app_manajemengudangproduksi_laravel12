<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Expense extends Model
{
    protected $fillable = [
        'number',
        'date',
        'account_id',
        'amount',
        'title',
        'description',
        'payment_method',
        'receipt_image',
        'status',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
        'amount' => 'decimal:2',
        'approved_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->number)) {
                $model->number = self::generateNumber();
            }
        });
    }

    public static function generateNumber(): string
    {
        $year = now()->format('Y');
        $prefix = "EXP-{$year}-";

        $last = self::where('number', 'like', $prefix . '%')
            ->orderBy('number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function account(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'account_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }

    public function markPaid(): void
    {
        $this->update(['status' => 'paid']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'approved' => 'Disetujui',
            'paid' => 'Dibayar',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'approved' => 'info',
            'paid' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentMethodLabelAttribute(): string
    {
        return match($this->payment_method) {
            'cash' => 'Tunai',
            'transfer' => 'Transfer Bank',
            'other' => 'Lainnya',
            default => $this->payment_method,
        };
    }

    public function getReceiptUrlAttribute(): ?string
    {
        return $this->receipt_image ? asset('storage/' . $this->receipt_image) : null;
    }
}

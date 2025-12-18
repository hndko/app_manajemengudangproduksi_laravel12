<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DeliveryNote extends Model
{
    protected $fillable = [
        'number',
        'date',
        'consumer_id',
        'warehouse_id',
        'shipping_address',
        'driver_name',
        'vehicle_number',
        'status',
        'notes',
        'created_by',
        'shipped_at',
        'delivered_at',
    ];

    protected $casts = [
        'date' => 'date',
        'shipped_at' => 'datetime',
        'delivered_at' => 'datetime',
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
        $prefix = "SJ-{$year}-";

        $last = self::where('number', 'like', $prefix . '%')
            ->orderBy('number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function consumer(): BelongsTo
    {
        return $this->belongsTo(Consumer::class);
    }

    public function warehouse(): BelongsTo
    {
        return $this->belongsTo(Warehouse::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(DeliveryNoteItem::class);
    }

    public function salesTransaction()
    {
        return $this->hasOne(SalesTransaction::class);
    }

    public function ship(): void
    {
        $this->update([
            'status' => 'shipped',
            'shipped_at' => now(),
        ]);
    }

    public function markDelivered(): void
    {
        $this->update([
            'status' => 'delivered',
            'delivered_at' => now(),
        ]);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'shipped' => 'Dikirim',
            'delivered' => 'Terkirim',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'shipped' => 'info',
            'delivered' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }

    public function getTotalValueAttribute(): float
    {
        return $this->items->sum('total_price');
    }
}

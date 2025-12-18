<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Returns extends Model
{
    protected $table = 'returns';

    protected $fillable = [
        'number',
        'date',
        'delivery_note_id',
        'consumer_id',
        'warehouse_id',
        'type',
        'status',
        'reason',
        'notes',
        'created_by',
        'approved_by',
        'approved_at',
    ];

    protected $casts = [
        'date' => 'date',
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
        $prefix = "RTR-{$year}-";

        $last = self::where('number', 'like', $prefix . '%')
            ->orderBy('number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class);
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

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(ReturnItem::class, 'return_id');
    }

    public function approve(): void
    {
        $this->update([
            'status' => 'approved',
            'approved_by' => auth()->id(),
            'approved_at' => now(),
        ]);
    }

    public function process(): void
    {
        $this->update(['status' => 'processed']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'customer_return' => 'Retur Pelanggan',
            'expedition_return' => 'Retur Ekspedisi',
            default => $this->type,
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'approved' => 'Disetujui',
            'processed' => 'Diproses',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'approved' => 'info',
            'processed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getTotalItemsAttribute(): int
    {
        return $this->items->sum('quantity');
    }
}

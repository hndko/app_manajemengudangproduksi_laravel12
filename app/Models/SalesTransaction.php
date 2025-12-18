<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SalesTransaction extends Model
{
    protected $fillable = [
        'number',
        'date',
        'consumer_id',
        'warehouse_id',
        'price_type_id',
        'installment_type_id',
        'subtotal',
        'discount_amount',
        'tax_amount',
        'total_amount',
        'paid_amount',
        'remaining_amount',
        'payment_status',
        'status',
        'delivery_note_id',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'date' => 'date',
        'subtotal' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'tax_amount' => 'decimal:2',
        'total_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'remaining_amount' => 'decimal:2',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->number)) {
                $model->number = self::generateNumber();
            }
        });

        static::saving(function ($model) {
            $model->remaining_amount = $model->total_amount - $model->paid_amount;

            if ($model->paid_amount <= 0) {
                $model->payment_status = 'unpaid';
            } elseif ($model->paid_amount >= $model->total_amount) {
                $model->payment_status = 'paid';
            } else {
                $model->payment_status = 'partial';
            }
        });
    }

    public static function generateNumber(): string
    {
        $year = now()->format('Y');
        $prefix = "INV-{$year}-";

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

    public function priceType(): BelongsTo
    {
        return $this->belongsTo(PriceType::class);
    }

    public function installmentType(): BelongsTo
    {
        return $this->belongsTo(InstallmentType::class);
    }

    public function deliveryNote(): BelongsTo
    {
        return $this->belongsTo(DeliveryNote::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function items(): HasMany
    {
        return $this->hasMany(SalesTransactionItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(SalesPayment::class);
    }

    public function calculateTotals(): void
    {
        $this->subtotal = $this->items->sum('total_price');
        $this->total_amount = $this->subtotal - $this->discount_amount + $this->tax_amount;
        $this->save();
    }

    public function addPayment(float $amount, string $method = 'cash', ?string $reference = null, ?string $notes = null): SalesPayment
    {
        $payment = $this->payments()->create([
            'number' => SalesPayment::generateNumber(),
            'date' => now(),
            'amount' => $amount,
            'method' => $method,
            'reference' => $reference,
            'notes' => $notes,
            'created_by' => auth()->id(),
        ]);

        $this->increment('paid_amount', $amount);

        return $payment;
    }

    public function confirm(): void
    {
        $this->update(['status' => 'confirmed']);
    }

    public function ship(): void
    {
        $this->update(['status' => 'shipped']);
    }

    public function complete(): void
    {
        $this->update(['status' => 'completed']);
    }

    public function cancel(): void
    {
        $this->update(['status' => 'cancelled']);
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'confirmed' => 'Dikonfirmasi',
            'shipped' => 'Dikirim',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'confirmed' => 'info',
            'shipped' => 'primary',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getPaymentStatusLabelAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid' => 'Belum Bayar',
            'partial' => 'Sebagian',
            'paid' => 'Lunas',
            default => $this->payment_status,
        };
    }

    public function getPaymentStatusColorAttribute(): string
    {
        return match($this->payment_status) {
            'unpaid' => 'danger',
            'partial' => 'warning',
            'paid' => 'success',
            default => 'secondary',
        };
    }
}

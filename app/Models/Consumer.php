<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consumer extends Model
{
    protected $fillable = [
        'code',
        'name',
        'email',
        'phone',
        'address',
        'city',
        'province',
        'postal_code',
        'npwp',
        'contact_person',
        'type',
        'credit_limit',
        'is_active',
        'notes',
    ];

    protected $casts = [
        'credit_limit' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->code)) {
                $model->code = self::generateCode();
            }
        });
    }

    public static function generateCode(): string
    {
        $prefix = 'CUST-';
        $last = self::where('code', 'like', $prefix . '%')
            ->orderBy('code', 'desc')
            ->first();

        if ($last) {
            $number = (int) substr($last->code, -4) + 1;
        } else {
            $number = 1;
        }

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function salesTransactions(): HasMany
    {
        return $this->hasMany(SalesTransaction::class);
    }

    public function deliveryNotes(): HasMany
    {
        return $this->hasMany(DeliveryNote::class);
    }

    public function getTypeLabelAttribute(): string
    {
        return $this->type === 'perusahaan' ? 'Perusahaan' : 'Perorangan';
    }

    public function getFullAddressAttribute(): string
    {
        $parts = array_filter([
            $this->address,
            $this->city,
            $this->province,
            $this->postal_code,
        ]);

        return implode(', ', $parts);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

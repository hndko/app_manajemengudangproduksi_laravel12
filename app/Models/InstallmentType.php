<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InstallmentType extends Model
{
    protected $fillable = [
        'name',
        'tenor',
        'interest_rate',
        'description',
        'is_active',
    ];

    protected $casts = [
        'tenor' => 'integer',
        'interest_rate' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function isCash(): bool
    {
        return $this->tenor === 0;
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

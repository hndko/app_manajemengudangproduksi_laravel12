<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ChartOfAccount extends Model
{
    protected $fillable = [
        'code',
        'name',
        'type',
        'normal_balance',
        'parent_id',
        'description',
        'is_active',
        'is_locked',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'is_locked' => 'boolean',
    ];

    /**
     * Get parent account
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(ChartOfAccount::class, 'parent_id');
    }

    /**
     * Get child accounts
     */
    public function children(): HasMany
    {
        return $this->hasMany(ChartOfAccount::class, 'parent_id');
    }

    /**
     * Get journal entry details
     */
    public function journalDetails(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class, 'account_id');
    }

    /**
     * Get type label in Indonesian
     */
    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'aset' => 'Aset',
            'liabilitas' => 'Liabilitas',
            'ekuitas' => 'Ekuitas',
            'pendapatan' => 'Pendapatan',
            'beban' => 'Beban',
            default => $this->type,
        };
    }

    /**
     * Get type badge color
     */
    public function getTypeColorAttribute(): string
    {
        return match($this->type) {
            'aset' => 'primary',
            'liabilitas' => 'danger',
            'ekuitas' => 'success',
            'pendapatan' => 'info',
            'beban' => 'warning',
            default => 'secondary',
        };
    }

    /**
     * Calculate balance
     */
    public function getBalanceAttribute(): float
    {
        $details = $this->journalDetails()
            ->whereHas('journalEntry', fn($q) => $q->where('status', 'posted'))
            ->get();

        $debit = $details->sum('debit');
        $credit = $details->sum('credit');

        if ($this->normal_balance === 'debit') {
            return $debit - $credit;
        }

        return $credit - $debit;
    }

    /**
     * Scope for active accounts
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope by type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }
}

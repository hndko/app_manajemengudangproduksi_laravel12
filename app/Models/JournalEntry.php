<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JournalEntry extends Model
{
    protected $fillable = [
        'number',
        'date',
        'description',
        'reference',
        'reference_type',
        'reference_id',
        'status',
        'fiscal_period_id',
        'created_by',
        'posted_by',
        'posted_at',
    ];

    protected $casts = [
        'date' => 'date',
        'posted_at' => 'datetime',
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->number)) {
                $model->number = self::generateNumber();
            }
        });
    }

    /**
     * Generate journal number
     */
    public static function generateNumber(): string
    {
        $year = now()->format('Y');
        $prefix = "JU-{$year}-";

        $lastJournal = self::where('number', 'like', $prefix . '%')
            ->orderBy('number', 'desc')
            ->first();

        if ($lastJournal) {
            $lastNumber = (int) substr($lastJournal->number, -4);
            $newNumber = $lastNumber + 1;
        } else {
            $newNumber = 1;
        }

        return $prefix . str_pad($newNumber, 4, '0', STR_PAD_LEFT);
    }

    /**
     * Get journal entry details
     */
    public function details(): HasMany
    {
        return $this->hasMany(JournalEntryDetail::class);
    }

    /**
     * Get fiscal period
     */
    public function fiscalPeriod(): BelongsTo
    {
        return $this->belongsTo(FiscalPeriod::class);
    }

    /**
     * Get creator
     */
    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get poster
     */
    public function poster(): BelongsTo
    {
        return $this->belongsTo(User::class, 'posted_by');
    }

    /**
     * Check if journal is balanced
     */
    public function isBalanced(): bool
    {
        $totalDebit = $this->details->sum('debit');
        $totalCredit = $this->details->sum('credit');

        return abs($totalDebit - $totalCredit) < 0.01;
    }

    /**
     * Get total debit
     */
    public function getTotalDebitAttribute(): float
    {
        return $this->details->sum('debit');
    }

    /**
     * Get total credit
     */
    public function getTotalCreditAttribute(): float
    {
        return $this->details->sum('credit');
    }

    /**
     * Post the journal
     */
    public function post(): bool
    {
        if (!$this->isBalanced()) {
            return false;
        }

        $this->update([
            'status' => 'posted',
            'posted_by' => auth()->id(),
            'posted_at' => now(),
        ]);

        return true;
    }

    /**
     * Void the journal
     */
    public function void(): void
    {
        $this->update(['status' => 'void']);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'posted' => 'success',
            'void' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get status label
     */
    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'draft' => 'Draft',
            'posted' => 'Diposting',
            'void' => 'Dibatalkan',
            default => $this->status,
        };
    }
}

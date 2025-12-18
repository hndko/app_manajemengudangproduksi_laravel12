<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FiscalPeriod extends Model
{
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_active',
        'is_closed',
        'closed_at',
        'closed_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
        'is_closed' => 'boolean',
        'closed_at' => 'datetime',
    ];

    /**
     * Get journal entries for this period
     */
    public function journalEntries(): HasMany
    {
        return $this->hasMany(JournalEntry::class);
    }

    /**
     * Get user who closed this period
     */
    public function closedBy()
    {
        return $this->belongsTo(User::class, 'closed_by');
    }

    /**
     * Scope for active period
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for open (not closed) periods
     */
    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    /**
     * Get the current active period
     */
    public static function current(): ?self
    {
        return self::where('is_active', true)->first();
    }
}

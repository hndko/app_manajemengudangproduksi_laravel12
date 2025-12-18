<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Production extends Model
{
    protected $fillable = [
        'number',
        'date',
        'production_team_id',
        'status',
        'notes',
        'created_by',
        'started_at',
        'completed_at',
    ];

    protected $casts = [
        'date' => 'date',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
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
        $prefix = "PRD-{$year}-";

        $last = self::where('number', 'like', $prefix . '%')
            ->orderBy('number', 'desc')
            ->first();

        $number = $last ? (int) substr($last->number, -4) + 1 : 1;
        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }

    public function productionTeam(): BelongsTo
    {
        return $this->belongsTo(ProductionTeam::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function materials(): HasMany
    {
        return $this->hasMany(ProductionMaterial::class);
    }

    public function products(): HasMany
    {
        return $this->hasMany(ProductionProduct::class);
    }

    public function start(): void
    {
        $this->update([
            'status' => 'in_progress',
            'started_at' => now(),
        ]);
    }

    public function complete(): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
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
            'in_progress' => 'Dalam Proses',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'draft' => 'warning',
            'in_progress' => 'info',
            'completed' => 'success',
            'cancelled' => 'danger',
            default => 'secondary',
        };
    }

    public function getTotalMaterialCostAttribute(): float
    {
        return $this->materials->sum(function ($item) {
            return $item->quantity_used * $item->unit_cost;
        });
    }

    public function getTotalProductValueAttribute(): float
    {
        return $this->products->sum(function ($item) {
            return $item->quantity_produced * $item->unit_cost;
        });
    }
}

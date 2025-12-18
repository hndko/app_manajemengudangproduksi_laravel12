<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ProductionTeam extends Model
{
    protected $fillable = [
        'name',
        'leader_id',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function leader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'leader_id');
    }

    public function productions(): HasMany
    {
        return $this->hasMany(Production::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}

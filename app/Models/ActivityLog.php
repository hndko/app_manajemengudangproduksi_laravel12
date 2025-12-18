<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ActivityLog extends Model
{
    protected $fillable = [
        'user_id',
        'action',
        'model_type',
        'model_id',
        'description',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'old_values' => 'array',
        'new_values' => 'array',
    ];

    /**
     * Get the user who performed this action
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the related model
     */
    public function subject()
    {
        if ($this->model_type && $this->model_id) {
            return $this->model_type::find($this->model_id);
        }
        return null;
    }

    /**
     * Log an activity
     */
    public static function log(
        string $action,
        string $description,
        ?Model $model = null,
        ?array $oldValues = null,
        ?array $newValues = null
    ): self {
        return self::create([
            'user_id' => auth()->id(),
            'action' => $action,
            'model_type' => $model ? get_class($model) : null,
            'model_id' => $model?->id,
            'description' => $description,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);
    }

    /**
     * Get action badge color
     */
    public function getActionColorAttribute(): string
    {
        return match($this->action) {
            'login' => 'success',
            'logout' => 'info',
            'create' => 'primary',
            'update' => 'warning',
            'delete' => 'danger',
            default => 'secondary',
        };
    }

    /**
     * Get action icon
     */
    public function getActionIconAttribute(): string
    {
        return match($this->action) {
            'login' => 'login',
            'logout' => 'logout',
            'create' => 'plus-circle',
            'update' => 'pencil',
            'delete' => 'trash',
            'view' => 'eye',
            default => 'activity',
        };
    }
}

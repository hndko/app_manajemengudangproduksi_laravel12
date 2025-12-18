<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Backup extends Model
{
    protected $fillable = [
        'filename',
        'path',
        'size',
        'type',
        'status',
        'notes',
        'created_by',
    ];

    protected $casts = [
        'size' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function getSizeFormattedAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, 2) . ' ' . $units[$i];
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'completed' => 'Selesai',
            'failed' => 'Gagal',
            default => $this->status,
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'warning',
            'completed' => 'success',
            'failed' => 'danger',
            default => 'secondary',
        };
    }

    public function getTypeLabelAttribute(): string
    {
        return match($this->type) {
            'full' => 'Backup Lengkap',
            'database' => 'Database Only',
            'files' => 'Files Only',
            default => $this->type,
        };
    }
}

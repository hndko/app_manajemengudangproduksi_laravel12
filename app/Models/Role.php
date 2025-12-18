<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Role extends Model
{
    protected $fillable = [
        'name',
        'display_name',
        'description',
        'permissions',
    ];

    protected $casts = [
        'permissions' => 'array',
    ];

    /**
     * Get users with this role
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    /**
     * Check if role has a specific permission
     */
    public function hasPermission(string $permission): bool
    {
        if (empty($this->permissions)) {
            return false;
        }

        return in_array($permission, $this->permissions);
    }

    /**
     * Check if this is admin role
     */
    public function isAdmin(): bool
    {
        return $this->name === 'admin_akuntansi';
    }
}

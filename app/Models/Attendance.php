<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    protected $fillable = [
        'user_id',
        'date',
        'clock_in',
        'clock_out',
        'status',
        'notes',
        'clock_in_location',
        'clock_out_location',
    ];

    protected $casts = [
        'date' => 'date',
        'clock_in' => 'datetime:H:i',
        'clock_out' => 'datetime:H:i',
    ];

    /**
     * Get the user
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get status badge color
     */
    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'hadir' => 'success',
            'izin' => 'info',
            'sakit' => 'warning',
            'alpha' => 'danger',
            'cuti' => 'primary',
            default => 'secondary',
        };
    }

    /**
     * Get formatted working hours
     */
    public function getWorkingHoursAttribute(): ?string
    {
        if (!$this->clock_in || !$this->clock_out) {
            return null;
        }

        $clockIn = \Carbon\Carbon::parse($this->clock_in);
        $clockOut = \Carbon\Carbon::parse($this->clock_out);
        $diff = $clockIn->diff($clockOut);

        return sprintf('%d jam %d menit', $diff->h, $diff->i);
    }

    /**
     * Check if user is late (after 08:00)
     */
    public function getIsLateAttribute(): bool
    {
        if (!$this->clock_in) {
            return false;
        }

        $clockIn = \Carbon\Carbon::parse($this->clock_in);
        $lateThreshold = \Carbon\Carbon::parse('08:00');

        return $clockIn->gt($lateThreshold);
    }

    /**
     * Clock in for today
     */
    public static function clockIn(int $userId, ?string $location = null): self
    {
        return self::updateOrCreate(
            ['user_id' => $userId, 'date' => now()->toDateString()],
            [
                'clock_in' => now()->format('H:i:s'),
                'status' => 'hadir',
                'clock_in_location' => $location,
            ]
        );
    }

    /**
     * Clock out for today
     */
    public static function clockOut(int $userId, ?string $location = null): ?self
    {
        $attendance = self::where('user_id', $userId)
            ->where('date', now()->toDateString())
            ->first();

        if ($attendance) {
            $attendance->update([
                'clock_out' => now()->format('H:i:s'),
                'clock_out_location' => $location,
            ]);
        }

        return $attendance;
    }
}

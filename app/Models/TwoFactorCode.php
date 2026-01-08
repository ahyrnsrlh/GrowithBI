<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

/**
 * TwoFactorCode Model
 * 
 * Represents a one-time password (OTP) for two-factor authentication.
 * 
 * Security notes:
 * - OTP codes are stored hashed, never in plaintext
 * - Each code is single-use and time-limited
 * - Maximum attempt tracking prevents brute-force attacks
 */
class TwoFactorCode extends Model
{
    protected $fillable = [
        'user_id',
        'code_hash',
        'expires_at',
        'attempts',
        'used_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'used_at' => 'datetime',
        'attempts' => 'integer',
    ];

    /**
     * Hidden from serialization for security
     */
    protected $hidden = [
        'code_hash',
    ];

    /**
     * Maximum allowed verification attempts
     */
    public const MAX_ATTEMPTS = 5;

    /**
     * OTP expiration time in minutes
     * Extended to 10 minutes for better user experience
     */
    public const EXPIRES_IN_MINUTES = 10;

    /**
     * Cooldown period between OTP resends in seconds
     */
    public const RESEND_COOLDOWN_SECONDS = 7;

    /**
     * Get the user that owns this code.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this code has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if this code has been used.
     */
    public function isUsed(): bool
    {
        return $this->used_at !== null;
    }

    /**
     * Check if maximum attempts have been exceeded.
     */
    public function hasExceededAttempts(): bool
    {
        return $this->attempts >= self::MAX_ATTEMPTS;
    }

    /**
     * Check if this code is still valid for verification.
     */
    public function isValid(): bool
    {
        return !$this->isExpired() && !$this->isUsed() && !$this->hasExceededAttempts();
    }

    /**
     * Increment the attempt counter.
     */
    public function incrementAttempts(): void
    {
        $this->increment('attempts');
    }

    /**
     * Mark this code as used.
     */
    public function markAsUsed(): void
    {
        $this->update(['used_at' => now()]);
    }

    /**
     * Get the remaining time before expiration.
     */
    public function getRemainingSeconds(): int
    {
        if ($this->isExpired()) {
            return 0;
        }
        return now()->diffInSeconds($this->expires_at);
    }

    /**
     * Scope to get active (unused, unexpired) codes.
     */
    public function scopeActive($query)
    {
        return $query->whereNull('used_at')
            ->where('expires_at', '>', now())
            ->where('attempts', '<', self::MAX_ATTEMPTS);
    }

    /**
     * Scope to get codes for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}

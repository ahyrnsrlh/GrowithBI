<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TrustedDevice Model
 * 
 * Represents a trusted device for two-factor authentication bypass.
 * 
 * Security notes:
 * - Device fingerprints are hashed for privacy
 * - Devices have automatic expiration
 * - Can be manually revoked by user or system
 */
class TrustedDevice extends Model
{
    protected $fillable = [
        'user_id',
        'device_hash',
        'device_name',
        'ip_address',
        'user_agent',
        'last_used_at',
        'expires_at',
    ];

    protected $casts = [
        'last_used_at' => 'datetime',
        'expires_at' => 'datetime',
    ];

    /**
     * Hidden from serialization for security
     */
    protected $hidden = [
        'device_hash',
    ];

    /**
     * Default trust duration in days
     */
    public const TRUST_DURATION_DAYS = 30;

    /**
     * Get the user that owns this device.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if this device trust has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Check if this device is still trusted.
     */
    public function isTrusted(): bool
    {
        return !$this->isExpired();
    }

    /**
     * Update the last used timestamp.
     */
    public function updateLastUsed(): bool
    {
        return $this->update(['last_used_at' => now()]);
    }

    /**
     * Extend the trust period.
     */
    public function extend(int $days = null): void
    {
        $days = $days ?? self::TRUST_DURATION_DAYS;
        $this->update([
            'expires_at' => now()->addDays($days),
            'last_used_at' => now(),
        ]);
    }

    /**
     * Get a friendly name for the device based on user agent.
     */
    public function getDeviceDisplayName(): string
    {
        if ($this->device_name) {
            return $this->device_name;
        }

        // Parse user agent for a friendly name
        $userAgent = $this->user_agent ?? 'Unknown Device';
        
        // Simple parsing for common browsers
        if (str_contains($userAgent, 'Chrome')) {
            return 'Chrome Browser';
        } elseif (str_contains($userAgent, 'Firefox')) {
            return 'Firefox Browser';
        } elseif (str_contains($userAgent, 'Safari')) {
            return 'Safari Browser';
        } elseif (str_contains($userAgent, 'Edge')) {
            return 'Edge Browser';
        }

        return 'Web Browser';
    }

    /**
     * Scope to get active (non-expired) trusted devices.
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', now());
    }

    /**
     * Scope to get devices for a specific user.
     */
    public function scopeForUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }
}

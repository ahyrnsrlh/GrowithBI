<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * TwoFactorAuditLog Model
 * 
 * Provides audit trail for all 2FA-related events.
 * Essential for security monitoring and compliance.
 */
class TwoFactorAuditLog extends Model
{
    protected $fillable = [
        'user_id',
        'event_type',
        'ip_address',
        'user_agent',
        'metadata',
        'success',
        'failure_reason',
    ];

    protected $casts = [
        'metadata' => 'array',
        'success' => 'boolean',
    ];

    /**
     * Event type constants
     */
    public const EVENT_OTP_SENT = 'otp_sent';
    public const EVENT_OTP_RESENT = 'otp_resent';
    public const EVENT_OTP_VERIFIED = 'otp_verified';
    public const EVENT_OTP_FAILED = 'otp_failed';
    public const EVENT_OTP_EXPIRED = 'otp_expired';
    public const EVENT_MAX_ATTEMPTS = 'max_attempts_exceeded';
    public const EVENT_DEVICE_TRUSTED = 'device_trusted';
    public const EVENT_DEVICE_REVOKED = 'device_revoked';
    public const EVENT_ALL_DEVICES_REVOKED = 'all_devices_revoked';
    public const EVENT_PASSWORD_CHANGED = 'password_changed';
    public const EVENT_EMAIL_CHANGED = 'email_changed';

    /**
     * Get the user that owns this log entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Create a log entry for a successful event.
     */
    public static function logSuccess(
        int $userId,
        string $eventType,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        array $metadata = []
    ): self {
        return self::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'metadata' => $metadata,
            'success' => true,
        ]);
    }

    /**
     * Create a log entry for a failed event.
     */
    public static function logFailure(
        int $userId,
        string $eventType,
        string $reason,
        ?string $ipAddress = null,
        ?string $userAgent = null,
        array $metadata = []
    ): self {
        return self::create([
            'user_id' => $userId,
            'event_type' => $eventType,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'metadata' => $metadata,
            'success' => false,
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Scope for filtering by event type.
     */
    public function scopeOfType($query, string $eventType)
    {
        return $query->where('event_type', $eventType);
    }

    /**
     * Scope for filtering by user.
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for filtering successful events.
     */
    public function scopeSuccessful($query)
    {
        return $query->where('success', true);
    }

    /**
     * Scope for filtering failed events.
     */
    public function scopeFailed($query)
    {
        return $query->where('success', false);
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\TrustedDevice;
use App\Models\TwoFactorAuditLog;
use App\Events\TrustedDeviceRegistered;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * TrustedDeviceService
 * 
 * Handles trusted device management for two-factor authentication bypass.
 * 
 * Security principles:
 * - Device fingerprint is hashed and stored securely
 * - Secure HTTP-only cookies for device tokens
 * - Automatic expiration of device trust
 * - Revocation on security-sensitive events
 */
class TrustedDeviceService
{
    /**
     * Cookie name for trusted device token
     */
    private const COOKIE_NAME = 'trusted_device_token';

    /**
     * Trust duration in days
     */
    private const TRUST_DURATION_DAYS = 30;

    /**
     * Roles that can use trusted devices
     */
    private const ROLES_WITH_TRUSTED_DEVICES = ['admin', 'pembimbing'];

    /**
     * Check if trusted devices are enabled for a user.
     */
    public function isTrustedDeviceEnabled(User $user): bool
    {
        return in_array($user->role, self::ROLES_WITH_TRUSTED_DEVICES);
    }

    /**
     * Check if the current device is trusted for a user.
     */
    public function isDeviceTrusted(User $user, Request $request): bool
    {
        if (!$this->isTrustedDeviceEnabled($user)) {
            return false;
        }

        $deviceToken = $request->cookie(self::COOKIE_NAME);
        
        if (!$deviceToken) {
            return false;
        }

        // Generate device hash from token
        $deviceHash = $this->hashDeviceToken($deviceToken);

        // Look for matching trusted device
        $trustedDevice = TrustedDevice::forUser($user->id)
            ->where('device_hash', $deviceHash)
            ->active()
            ->first();

        if (!$trustedDevice) {
            return false;
        }

        // Update last used timestamp
        $trustedDevice->updateLastUsed();

        return true;
    }

    /**
     * Register the current device as trusted.
     */
    public function trustDevice(User $user, Request $request): array
    {
        if (!$this->isTrustedDeviceEnabled($user)) {
            return [
                'success' => false,
                'message' => 'Trusted devices are not available for your role.',
            ];
        }

        // Generate a unique device token
        $deviceToken = $this->generateDeviceToken();
        $deviceHash = $this->hashDeviceToken($deviceToken);

        // Check for existing device with same fingerprint
        $existingDevice = TrustedDevice::forUser($user->id)
            ->where('device_hash', $deviceHash)
            ->first();

        if ($existingDevice) {
            // Extend existing device trust
            $existingDevice->extend(self::TRUST_DURATION_DAYS);
            
            return [
                'success' => true,
                'message' => 'Device trust extended.',
                'cookie' => $this->createDeviceCookie($deviceToken),
            ];
        }

        // Create new trusted device
        $trustedDevice = TrustedDevice::create([
            'user_id' => $user->id,
            'device_hash' => $deviceHash,
            'device_name' => $this->parseDeviceName($request->userAgent()),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'last_used_at' => now(),
            'expires_at' => now()->addDays(self::TRUST_DURATION_DAYS),
        ]);

        // Fire event
        event(new TrustedDeviceRegistered($user, $trustedDevice, $request->ip()));

        // Log the event
        TwoFactorAuditLog::logSuccess(
            $user->id,
            TwoFactorAuditLog::EVENT_DEVICE_TRUSTED,
            $request->ip(),
            $request->userAgent(),
            [
                'device_name' => $trustedDevice->device_name,
                'expires_at' => $trustedDevice->expires_at->toIso8601String(),
            ]
        );

        return [
            'success' => true,
            'message' => 'Device registered as trusted.',
            'cookie' => $this->createDeviceCookie($deviceToken),
        ];
    }

    /**
     * Revoke trust for a specific device.
     */
    public function revokeDevice(User $user, int $deviceId, Request $request): bool
    {
        $device = TrustedDevice::where('id', $deviceId)
            ->where('user_id', $user->id)
            ->first();

        if (!$device) {
            return false;
        }

        $deviceName = $device->device_name;
        $device->delete();

        // Log the event
        TwoFactorAuditLog::logSuccess(
            $user->id,
            TwoFactorAuditLog::EVENT_DEVICE_REVOKED,
            $request->ip(),
            $request->userAgent(),
            ['device_name' => $deviceName]
        );

        return true;
    }

    /**
     * Revoke all trusted devices for a user.
     */
    public function revokeAllDevices(User $user, Request $request, string $reason = 'manual'): int
    {
        $count = TrustedDevice::forUser($user->id)->delete();

        if ($count > 0) {
            // Log the event
            TwoFactorAuditLog::logSuccess(
                $user->id,
                TwoFactorAuditLog::EVENT_ALL_DEVICES_REVOKED,
                $request->ip(),
                $request->userAgent(),
                ['reason' => $reason, 'devices_revoked' => $count]
            );
        }

        return $count;
    }

    /**
     * Get all trusted devices for a user.
     */
    public function getTrustedDevices(User $user): array
    {
        return TrustedDevice::forUser($user->id)
            ->active()
            ->orderBy('last_used_at', 'desc')
            ->get()
            ->map(function ($device) {
                return [
                    'id' => $device->id,
                    'name' => $device->getDeviceDisplayName(),
                    'ip_address' => $device->ip_address,
                    'last_used_at' => $device->last_used_at?->toIso8601String(),
                    'expires_at' => $device->expires_at->toIso8601String(),
                    'created_at' => $device->created_at->toIso8601String(),
                ];
            })
            ->toArray();
    }

    /**
     * Handle password change - revoke all trusted devices.
     */
    public function onPasswordChanged(User $user, Request $request): void
    {
        $this->revokeAllDevices($user, $request, 'password_changed');

        TwoFactorAuditLog::logSuccess(
            $user->id,
            TwoFactorAuditLog::EVENT_PASSWORD_CHANGED,
            $request->ip(),
            $request->userAgent()
        );
    }

    /**
     * Handle email change - revoke all trusted devices.
     */
    public function onEmailChanged(User $user, Request $request): void
    {
        $this->revokeAllDevices($user, $request, 'email_changed');

        TwoFactorAuditLog::logSuccess(
            $user->id,
            TwoFactorAuditLog::EVENT_EMAIL_CHANGED,
            $request->ip(),
            $request->userAgent()
        );
    }

    /**
     * Generate a unique device token.
     */
    private function generateDeviceToken(): string
    {
        return Str::random(64);
    }

    /**
     * Hash the device token for storage.
     */
    private function hashDeviceToken(string $token): string
    {
        return hash('sha256', $token);
    }

    /**
     * Create a secure HTTP-only cookie for the device token.
     */
    private function createDeviceCookie(string $token): \Symfony\Component\HttpFoundation\Cookie
    {
        return Cookie::make(
            self::COOKIE_NAME,
            $token,
            self::TRUST_DURATION_DAYS * 24 * 60, // Minutes
            '/',
            null,
            true, // Secure (HTTPS only)
            true, // HTTP only
            false, // Raw
            'Strict' // SameSite
        );
    }

    /**
     * Get the forget cookie (for logout).
     */
    public function getForgetCookie(): \Symfony\Component\HttpFoundation\Cookie
    {
        return Cookie::forget(self::COOKIE_NAME);
    }

    /**
     * Parse user agent to get a friendly device name.
     */
    private function parseDeviceName(?string $userAgent): string
    {
        if (!$userAgent) {
            return 'Unknown Device';
        }

        $browser = 'Browser';
        $os = 'Unknown OS';

        // Detect browser
        if (str_contains($userAgent, 'Chrome') && !str_contains($userAgent, 'Edge')) {
            $browser = 'Chrome';
        } elseif (str_contains($userAgent, 'Firefox')) {
            $browser = 'Firefox';
        } elseif (str_contains($userAgent, 'Safari') && !str_contains($userAgent, 'Chrome')) {
            $browser = 'Safari';
        } elseif (str_contains($userAgent, 'Edge')) {
            $browser = 'Edge';
        } elseif (str_contains($userAgent, 'Opera') || str_contains($userAgent, 'OPR')) {
            $browser = 'Opera';
        }

        // Detect OS
        if (str_contains($userAgent, 'Windows')) {
            $os = 'Windows';
        } elseif (str_contains($userAgent, 'Mac')) {
            $os = 'macOS';
        } elseif (str_contains($userAgent, 'Linux')) {
            $os = 'Linux';
        } elseif (str_contains($userAgent, 'Android')) {
            $os = 'Android';
        } elseif (str_contains($userAgent, 'iOS') || str_contains($userAgent, 'iPhone') || str_contains($userAgent, 'iPad')) {
            $os = 'iOS';
        }

        return "{$browser} on {$os}";
    }

    /**
     * Clean up expired trusted devices (can be called via scheduled job).
     */
    public function cleanupExpiredDevices(): int
    {
        return TrustedDevice::where('expires_at', '<', now())
            ->delete();
    }
}

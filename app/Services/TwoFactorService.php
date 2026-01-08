<?php

namespace App\Services;

use App\Models\User;
use App\Models\TwoFactorCode;
use App\Models\TwoFactorAuditLog;
use App\Jobs\SendTwoFactorOtpJob;
use App\Events\TwoFactorRequested;
use App\Events\TwoFactorVerified;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

/**
 * TwoFactorService
 * 
 * Handles all two-factor authentication logic including:
 * - OTP generation and validation
 * - Rate limiting for OTP requests
 * - Audit logging for security events
 * 
 * Security principles:
 * - OTP codes are always hashed before storage
 * - Single-use enforcement
 * - Time-limited validity
 * - Brute-force protection via attempt limits
 */
class TwoFactorService
{
    /**
     * Roles that require two-factor authentication
     */
    private const ROLES_REQUIRING_2FA = ['admin', 'pembimbing'];

    /**
     * OTP length
     */
    private const OTP_LENGTH = 6;

    /**
     * Check if two-factor authentication is required for a user.
     */
    public function isTwoFactorRequired(User $user): bool
    {
        // Check if user's role requires 2FA
        if (!in_array($user->role, self::ROLES_REQUIRING_2FA)) {
            return false;
        }

        // Check if 2FA is enabled for the user
        return $user->two_factor_enabled ?? true;
    }

    /**
     * Generate and send a new OTP code to the user.
     */
    public function sendOtpCode(User $user, Request $request): TwoFactorCode
    {
        // Invalidate any existing active codes
        $this->invalidateActiveCodes($user);

        // Generate new OTP
        $plainCode = $this->generateOtpCode();
        
        // Create the code record
        $twoFactorCode = TwoFactorCode::create([
            'user_id' => $user->id,
            'code_hash' => Hash::make($plainCode),
            'expires_at' => now()->addMinutes(TwoFactorCode::EXPIRES_IN_MINUTES),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

        // Dispatch job to send OTP via email (queued)
        SendTwoFactorOtpJob::dispatch($user, $plainCode);

        // Fire event
        event(new TwoFactorRequested($user, $request->ip(), $request->userAgent()));

        // Log the event
        TwoFactorAuditLog::logSuccess(
            $user->id,
            TwoFactorAuditLog::EVENT_OTP_SENT,
            $request->ip(),
            $request->userAgent(),
            ['expires_at' => $twoFactorCode->expires_at->toIso8601String()]
        );

        return $twoFactorCode;
    }

    /**
     * Resend OTP code with cooldown check.
     */
    public function resendOtpCode(User $user, Request $request): array
    {
        // Check cooldown
        $lastCode = TwoFactorCode::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if ($lastCode) {
            $secondsSinceLastCode = now()->diffInSeconds($lastCode->created_at);
            
            if ($secondsSinceLastCode < TwoFactorCode::RESEND_COOLDOWN_SECONDS) {
                $remainingCooldown = TwoFactorCode::RESEND_COOLDOWN_SECONDS - $secondsSinceLastCode;
                
                return [
                    'success' => false,
                    'message' => 'Silakan tunggu sebelum meminta kode baru.',
                    'cooldown_remaining' => $remainingCooldown,
                ];
            }
        }

        // Send new code
        $newCode = $this->sendOtpCode($user, $request);

        // Log resend event
        TwoFactorAuditLog::logSuccess(
            $user->id,
            TwoFactorAuditLog::EVENT_OTP_RESENT,
            $request->ip(),
            $request->userAgent()
        );

        return [
            'success' => true,
            'message' => 'Kode verifikasi baru telah dikirim ke email Anda.',
            'expires_at' => $newCode->expires_at->toIso8601String(),
            'remaining_seconds' => $newCode->getRemainingSeconds(),
        ];
    }

    /**
     * Verify an OTP code.
     */
    public function verifyOtpCode(User $user, string $code, Request $request): array
    {
        // Get the active code for this user
        $twoFactorCode = TwoFactorCode::forUser($user->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$twoFactorCode) {
            TwoFactorAuditLog::logFailure(
                $user->id,
                TwoFactorAuditLog::EVENT_OTP_FAILED,
                'No active OTP code found',
                $request->ip(),
                $request->userAgent()
            );

            return [
                'success' => false,
                'message' => 'Kode verifikasi tidak ditemukan atau sudah kadaluarsa. Silakan minta kode baru.',
                'expired' => true,
            ];
        }

        // Check if expired
        if ($twoFactorCode->isExpired()) {
            TwoFactorAuditLog::logFailure(
                $user->id,
                TwoFactorAuditLog::EVENT_OTP_EXPIRED,
                'OTP code expired',
                $request->ip(),
                $request->userAgent()
            );

            return [
                'success' => false,
                'message' => 'Kode verifikasi sudah kadaluarsa. Silakan minta kode baru.',
                'expired' => true,
            ];
        }

        // Check attempts limit
        if ($twoFactorCode->hasExceededAttempts()) {
            TwoFactorAuditLog::logFailure(
                $user->id,
                TwoFactorAuditLog::EVENT_MAX_ATTEMPTS,
                'Maximum attempts exceeded',
                $request->ip(),
                $request->userAgent()
            );

            return [
                'success' => false,
                'message' => 'Terlalu banyak percobaan gagal. Silakan minta kode baru.',
                'max_attempts_exceeded' => true,
            ];
        }

        // Verify the code
        if (!Hash::check($code, $twoFactorCode->code_hash)) {
            $twoFactorCode->incrementAttempts();
            
            $remainingAttempts = TwoFactorCode::MAX_ATTEMPTS - $twoFactorCode->attempts;

            TwoFactorAuditLog::logFailure(
                $user->id,
                TwoFactorAuditLog::EVENT_OTP_FAILED,
                'Invalid OTP code',
                $request->ip(),
                $request->userAgent(),
                ['remaining_attempts' => $remainingAttempts]
            );

            return [
                'success' => false,
                'message' => "Kode verifikasi salah. Sisa percobaan: {$remainingAttempts}",
                'remaining_attempts' => $remainingAttempts,
            ];
        }

        // Mark code as used
        $twoFactorCode->markAsUsed();

        // Update user's 2FA verification timestamp
        $user->update(['two_factor_verified_at' => now()]);

        // Fire event
        event(new TwoFactorVerified($user, $request->ip(), $request->userAgent()));

        // Log success
        TwoFactorAuditLog::logSuccess(
            $user->id,
            TwoFactorAuditLog::EVENT_OTP_VERIFIED,
            $request->ip(),
            $request->userAgent()
        );

        return [
            'success' => true,
            'message' => 'Verifikasi berhasil!',
        ];
    }

    /**
     * Get remaining time for the current active OTP.
     */
    public function getRemainingTime(User $user): ?int
    {
        $activeCode = TwoFactorCode::forUser($user->id)
            ->active()
            ->orderBy('created_at', 'desc')
            ->first();

        return $activeCode?->getRemainingSeconds();
    }

    /**
     * Ensure an active OTP exists for the user, send one if not.
     * This prevents regenerating OTP on every page load.
     */
    public function ensureActiveOtpExists(User $user, Request $request): void
    {
        $activeCode = TwoFactorCode::forUser($user->id)
            ->active()
            ->first();

        if (!$activeCode) {
            \Log::info('2FA: No active OTP found, generating new one', ['user_id' => $user->id]);
            $this->sendOtpCode($user, $request);
        } else {
            \Log::info('2FA: Active OTP exists', [
                'user_id' => $user->id,
                'code_id' => $activeCode->id,
                'remaining_seconds' => $activeCode->getRemainingSeconds()
            ]);
        }
    }

    /**
     * Check if user can resend OTP (cooldown check).
     */
    public function canResendOtp(User $user): array
    {
        $lastCode = TwoFactorCode::forUser($user->id)
            ->orderBy('created_at', 'desc')
            ->first();

        if (!$lastCode) {
            return ['can_resend' => true, 'cooldown_remaining' => 0];
        }

        $secondsSinceLastCode = now()->diffInSeconds($lastCode->created_at);
        
        if ($secondsSinceLastCode >= TwoFactorCode::RESEND_COOLDOWN_SECONDS) {
            return ['can_resend' => true, 'cooldown_remaining' => 0];
        }

        return [
            'can_resend' => false,
            'cooldown_remaining' => TwoFactorCode::RESEND_COOLDOWN_SECONDS - $secondsSinceLastCode,
        ];
    }

    /**
     * Generate a random numeric OTP code.
     */
    private function generateOtpCode(): string
    {
        $digits = '';
        for ($i = 0; $i < self::OTP_LENGTH; $i++) {
            $digits .= random_int(0, 9);
        }
        return $digits;
    }

    /**
     * Invalidate all active OTP codes for a user.
     */
    private function invalidateActiveCodes(User $user): void
    {
        TwoFactorCode::forUser($user->id)
            ->active()
            ->update(['used_at' => now()]);
    }

    /**
     * Clean up expired codes (can be called via scheduled job).
     */
    public function cleanupExpiredCodes(): int
    {
        return TwoFactorCode::where('expires_at', '<', now()->subDay())
            ->delete();
    }
}

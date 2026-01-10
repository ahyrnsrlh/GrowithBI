<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\CaptchaVerificationService;
use App\Services\TwoFactorService;
use App\Services\TrustedDeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

/**
 * TwoFactorController
 * 
 * Handles the two-factor authentication challenge flow:
 * - Display OTP challenge page
 * - Verify OTP codes
 * - Resend OTP codes
 * - Handle trusted device registration
 */
class TwoFactorController extends Controller
{
    protected TwoFactorService $twoFactorService;
    protected TrustedDeviceService $trustedDeviceService;
    protected CaptchaVerificationService $captchaService;

    public function __construct(
        TwoFactorService $twoFactorService,
        TrustedDeviceService $trustedDeviceService,
        CaptchaVerificationService $captchaService
    ) {
        $this->twoFactorService = $twoFactorService;
        $this->trustedDeviceService = $trustedDeviceService;
        $this->captchaService = $captchaService;
    }

    /**
     * Display the two-factor challenge page.
     */
    public function create(Request $request): Response|\Illuminate\Http\RedirectResponse
    {
        $user = Auth::user();

        // If 2FA not required, redirect away
        if (!$this->twoFactorService->isTwoFactorRequired($user)) {
            return redirect()->route('dashboard');
        }

        // Check if device is already trusted
        if ($this->trustedDeviceService->isDeviceTrusted($user, $request)) {
            $request->session()->put('two_factor_verified', true);
            return redirect()->intended($this->getRedirectRoute($user));
        }

        // Check if OTP needs to be sent (no active code exists)
        $this->twoFactorService->ensureActiveOtpExists($user, $request);

        // Get OTP timing info
        $remainingTime = $this->twoFactorService->getRemainingTime($user);
        $resendInfo = $this->twoFactorService->canResendOtp($user);

        return Inertia::render('Auth/TwoFactorChallenge', [
            'email' => $this->maskEmail($user->email),
            'remainingTime' => $remainingTime ?? 0,
            'canResend' => $resendInfo['can_resend'],
            'cooldownRemaining' => $resendInfo['cooldown_remaining'],
            'trustedDeviceEnabled' => $this->trustedDeviceService->isTrustedDeviceEnabled($user),
            'expiresInMinutes' => \App\Models\TwoFactorCode::EXPIRES_IN_MINUTES,
            'maxAttempts' => \App\Models\TwoFactorCode::MAX_ATTEMPTS,
            'recaptchaSiteKey' => $this->captchaService->getSiteKey(),
            'recaptchaEnabled' => $this->captchaService->isEnabled() && $this->captchaService->shouldEnforceForRole($user->role),
        ]);
    }

    /**
     * Verify the OTP code submitted by the user.
     */
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'string', 'size:6', 'regex:/^[0-9]+$/'],
            'trust_device' => ['boolean'],
        ], [
            'code.required' => 'Kode verifikasi wajib diisi.',
            'code.size' => 'Kode verifikasi harus 6 digit.',
            'code.regex' => 'Kode verifikasi harus berupa angka.',
        ]);

        $user = Auth::user();
        $code = trim($request->input('code')); // Trim whitespace
        $trustDevice = $request->boolean('trust_device');

        // Verify the OTP
        $result = $this->twoFactorService->verifyOtpCode($user, $code, $request);

        if (!$result['success']) {
            return back()->withErrors([
                'code' => $result['message'],
            ])->with([
                'expired' => $result['expired'] ?? false,
                'max_attempts_exceeded' => $result['max_attempts_exceeded'] ?? false,
            ]);
        }

        // Mark session as 2FA verified
        $request->session()->put('two_factor_verified', true);

        // Handle trusted device registration
        $response = redirect()->intended($this->getRedirectRoute($user));

        if ($trustDevice && $this->trustedDeviceService->isTrustedDeviceEnabled($user)) {
            $deviceResult = $this->trustedDeviceService->trustDevice($user, $request);
            
            if ($deviceResult['success'] && isset($deviceResult['cookie'])) {
                $response = $response->withCookie($deviceResult['cookie']);
            }
        }

        return $response->with('success', 'Verifikasi berhasil! Selamat datang.');
    }

    /**
     * Resend the OTP code.
     */
    public function resend(Request $request)
    {
        $user = Auth::user();

        $result = $this->twoFactorService->resendOtpCode($user, $request);

        // For Inertia/normal requests, use back() with messages
        if (!$result['success']) {
            return back()->withErrors([
                'resend' => $result['message'],
            ])->with([
                'cooldown_remaining' => $result['cooldown_remaining'] ?? null,
            ]);
        }

        return back()->with([
            'status' => $result['message'],
            'expires_at' => $result['expires_at'] ?? null,
        ]);
    }

    /**
     * Initiate 2FA - send OTP code.
     * Called after successful password authentication.
     */
    public function initiate(Request $request)
    {
        $user = Auth::user();

        // Send OTP
        $this->twoFactorService->sendOtpCode($user, $request);

        return redirect()->route('two-factor.challenge');
    }

    /**
     * Get the redirect route based on user role.
     */
    protected function getRedirectRoute($user): string
    {
        switch ($user->role) {
            case 'admin':
                return route('admin.dashboard');
            case 'pembimbing':
                return route('profile.edit');
            case 'peserta':
                return route('profile.edit');
            default:
                return route('dashboard');
        }
    }

    /**
     * Mask email for privacy (e.g., j***@example.com)
     */
    protected function maskEmail(string $email): string
    {
        $parts = explode('@', $email);
        if (count($parts) !== 2) {
            return '***@***.***';
        }

        $name = $parts[0];
        $domain = $parts[1];

        if (strlen($name) <= 2) {
            $maskedName = str_repeat('*', strlen($name));
        } else {
            $maskedName = substr($name, 0, 1) . str_repeat('*', strlen($name) - 2) . substr($name, -1);
        }

        return $maskedName . '@' . $domain;
    }
}

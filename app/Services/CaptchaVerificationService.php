<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * CaptchaVerificationService
 * 
 * Handles Google reCAPTCHA v3 verification for authentication flows.
 * Applied only to peserta role for login, forgot password, and OTP verification.
 * 
 * Security considerations:
 * - Never validate captcha in frontend only
 * - Never expose secret key to client
 * - Do not persist captcha tokens
 * - Always validate score, action, and success flag
 * - Fail securely if Google API is unreachable
 */
class CaptchaVerificationService
{
    /**
     * Minimum score threshold for valid captcha (0.0 - 1.0)
     */
    protected const SCORE_THRESHOLD = 0.5;

    /**
     * Google reCAPTCHA verification API endpoint
     */
    protected const VERIFY_URL = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Roles that are excluded from captcha verification
     */
    protected const EXCLUDED_ROLES = ['admin', 'pembimbing'];

    /**
     * Valid actions that can be verified
     */
    protected const VALID_ACTIONS = ['login', 'forgot_password', 'otp_verification', 'otp_resend'];

    /**
     * Check if captcha is enabled in the application
     */
    public function isEnabled(): bool
    {
        return !empty(config('services.recaptcha.secret_key')) 
            && !empty(config('services.recaptcha.site_key'));
    }

    /**
     * Get the reCAPTCHA site key for frontend
     */
    public function getSiteKey(): ?string
    {
        return config('services.recaptcha.site_key');
    }

    /**
     * Check if captcha should be enforced for a specific role
     */
    public function shouldEnforceForRole(?string $role): bool
    {
        // If no role provided (pre-authentication), enforce captcha
        if ($role === null) {
            return true;
        }

        // Excluded roles skip captcha
        return !in_array($role, self::EXCLUDED_ROLES);
    }

    /**
     * Check if captcha should be required for a given email
     * Used for pre-authentication checks (login, forgot password)
     */
    public function shouldRequireForEmail(?string $email): bool
    {
        if (!$this->isEnabled() || !$email) {
            return false;
        }

        // Check if user exists and get their role
        $user = \App\Models\User::where('email', $email)->first();
        
        if (!$user) {
            // User doesn't exist, but we still require captcha for security
            // (prevents enumeration attacks)
            return true;
        }

        return $this->shouldEnforceForRole($user->role);
    }

    /**
     * Verify reCAPTCHA token with Google API
     * 
     * @param string|null $token The reCAPTCHA token from frontend
     * @param string $expectedAction The expected action (login, forgot_password, etc.)
     * @param string|null $email Optional email for role-based enforcement
     * @return array{success: bool, message: string, score?: float}
     */
    public function verify(?string $token, string $expectedAction, ?string $email = null): array
    {
        // Check if captcha is enabled
        if (!$this->isEnabled()) {
            Log::debug('CaptchaVerificationService: Captcha is disabled, skipping verification');
            return [
                'success' => true,
                'message' => 'Captcha disabled',
            ];
        }

        // Check if captcha should be required for this email/role
        if ($email && !$this->shouldRequireForEmail($email)) {
            Log::debug('CaptchaVerificationService: Captcha not required for role', ['email' => $email]);
            return [
                'success' => true,
                'message' => 'Captcha not required for this role',
            ];
        }

        // Validate action
        if (!in_array($expectedAction, self::VALID_ACTIONS)) {
            Log::warning('CaptchaVerificationService: Invalid action', ['action' => $expectedAction]);
            return [
                'success' => false,
                'message' => 'Invalid captcha action',
            ];
        }

        // Validate token presence
        if (empty($token)) {
            Log::warning('CaptchaVerificationService: Missing captcha token', [
                'action' => $expectedAction,
                'email' => $email,
            ]);
            return [
                'success' => false,
                'message' => 'Missing captcha token',
            ];
        }

        // Debug: Log the token being verified (first/last 20 chars only for security)
        Log::debug('CaptchaVerificationService: Verifying token', [
            'token_length' => strlen($token),
            'token_preview' => substr($token, 0, 20) . '...' . substr($token, -20),
            'action' => $expectedAction,
            'email' => $email,
        ]);

        try {
            $response = Http::timeout(10)->asForm()->post(self::VERIFY_URL, [
                'secret' => config('services.recaptcha.secret_key'),
                'response' => $token,
                'remoteip' => request()->ip(),
            ]);

            if (!$response->successful()) {
                Log::error('CaptchaVerificationService: Google API request failed', [
                    'status' => $response->status(),
                    'action' => $expectedAction,
                ]);
                return [
                    'success' => false,
                    'message' => 'Captcha verification failed',
                ];
            }

            $result = $response->json();

            // Log the verification attempt
            Log::info('CaptchaVerificationService: Verification result', [
                'success' => $result['success'] ?? false,
                'score' => $result['score'] ?? null,
                'action' => $result['action'] ?? null,
                'expected_action' => $expectedAction,
                'email' => $email,
            ]);

            // Check success flag
            if (!($result['success'] ?? false)) {
                $errorCodes = $result['error-codes'] ?? [];
                
                Log::warning('CaptchaVerificationService: Captcha verification failed', [
                    'error_codes' => $errorCodes,
                    'email' => $email,
                ]);
                
                // Provide specific error message for browser-error
                // This typically means the site key is not valid for this domain
                if (in_array('browser-error', $errorCodes)) {
                    Log::error('CaptchaVerificationService: BROWSER-ERROR detected!', [
                        'message' => 'Site key is likely not registered for this domain (localhost)',
                        'hint' => 'Go to https://www.google.com/recaptcha/admin and add "localhost" to your domains',
                        'email' => $email,
                    ]);
                }
                
                // Check for invalid-input-secret (wrong secret key)
                if (in_array('invalid-input-secret', $errorCodes)) {
                    Log::error('CaptchaVerificationService: INVALID SECRET KEY!', [
                        'message' => 'The secret key in .env is invalid or malformed',
                        'hint' => 'Check RECAPTCHA_SECRET_KEY in your .env file',
                    ]);
                }
                
                return [
                    'success' => false,
                    'message' => 'Captcha verification failed',
                    'error_codes' => $errorCodes,
                ];
            }

            // Validate action matches
            if (($result['action'] ?? '') !== $expectedAction) {
                Log::warning('CaptchaVerificationService: Action mismatch', [
                    'expected' => $expectedAction,
                    'received' => $result['action'] ?? 'none',
                    'email' => $email,
                ]);
                return [
                    'success' => false,
                    'message' => 'Invalid captcha action',
                ];
            }

            // Validate score threshold
            $score = $result['score'] ?? 0;
            if ($score < self::SCORE_THRESHOLD) {
                Log::warning('CaptchaVerificationService: Low captcha score', [
                    'score' => $score,
                    'threshold' => self::SCORE_THRESHOLD,
                    'email' => $email,
                ]);
                return [
                    'success' => false,
                    'message' => 'Captcha score too low',
                    'score' => $score,
                ];
            }

            return [
                'success' => true,
                'message' => 'Captcha verification passed',
                'score' => $score,
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('CaptchaVerificationService: Google API unreachable', [
                'error' => $e->getMessage(),
                'action' => $expectedAction,
            ]);
            // Fail securely - block the request if Google API is down
            return [
                'success' => false,
                'message' => 'Captcha verification service unavailable',
            ];
        } catch (\Exception $e) {
            Log::error('CaptchaVerificationService: Unexpected error', [
                'error' => $e->getMessage(),
                'action' => $expectedAction,
            ]);
            return [
                'success' => false,
                'message' => 'Captcha verification error',
            ];
        }
    }

    /**
     * Get the score threshold
     */
    public function getScoreThreshold(): float
    {
        return self::SCORE_THRESHOLD;
    }

    /**
     * Get excluded roles
     */
    public function getExcludedRoles(): array
    {
        return self::EXCLUDED_ROLES;
    }
}

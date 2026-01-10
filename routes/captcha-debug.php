<?php

use App\Services\CaptchaVerificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Route;

/**
 * Captcha Debugging Routes (DEVELOPMENT ONLY)
 * 
 * These routes help diagnose reCAPTCHA configuration issues.
 * REMOVE or DISABLE in production!
 */

// Helper function to interpret error codes - only define if not exists
if (!function_exists('interpretCaptchaErrorCodes')) {
    function interpretCaptchaErrorCodes(array $codes): array
    {
        $meanings = [
            'missing-input-secret' => 'The secret parameter is missing.',
            'invalid-input-secret' => 'The secret parameter is invalid or malformed.',
            'missing-input-response' => 'The response parameter is missing.',
            'invalid-input-response' => 'The response parameter is invalid or malformed.',
            'bad-request' => 'The request is invalid or malformed.',
            'timeout-or-duplicate' => 'The response is no longer valid: either is too old or has been used previously.',
            'browser-error' => 'CRITICAL: Token was not generated correctly on the browser. This usually means the site key is not valid for this domain (localhost). You need to register localhost in your reCAPTCHA admin console.',
        ];
        
        return array_map(function ($code) use ($meanings) {
            return [
                'code' => $code,
                'meaning' => $meanings[$code] ?? 'Unknown error code',
            ];
        }, $codes);
    }
}

Route::prefix('debug/captcha')->middleware('web')->group(function () {
    
    /**
     * Check captcha configuration status
     */
    Route::get('/status', function () {
        $service = app(CaptchaVerificationService::class);
        
        $siteKey = config('services.recaptcha.site_key');
        $secretKey = config('services.recaptcha.secret_key');
        
        return response()->json([
            'status' => 'ok',
            'configuration' => [
                'enabled' => $service->isEnabled(),
                'site_key_present' => !empty($siteKey),
                'site_key_length' => strlen($siteKey ?? ''),
                'site_key_preview' => $siteKey ? substr($siteKey, 0, 10) . '...' : null,
                'secret_key_present' => !empty($secretKey),
                'secret_key_length' => strlen($secretKey ?? ''),
            ],
            'score_threshold' => $service->getScoreThreshold(),
            'excluded_roles' => $service->getExcludedRoles(),
            'environment' => app()->environment(),
            'app_url' => config('app.url'),
            'instructions' => [
                'step_1' => 'Visit https://www.google.com/recaptcha/admin',
                'step_2' => 'Create a NEW reCAPTCHA v3 key (NOT v2)',
                'step_3' => 'Add domains: localhost, 127.0.0.1, your-production-domain.com',
                'step_4' => 'Copy Site Key to RECAPTCHA_SITE_KEY in .env',
                'step_5' => 'Copy Secret Key to RECAPTCHA_SECRET_KEY in .env',
                'step_6' => 'Run: php artisan config:clear && php artisan config:cache',
            ]
        ]);
    });

    /**
     * Test captcha token verification
     */
    Route::post('/verify', function (Request $request) {
        $token = $request->input('token');
        $action = $request->input('action', 'test');
        
        if (empty($token)) {
            return response()->json([
                'error' => 'No token provided',
                'instruction' => 'Send POST with {"token": "your_recaptcha_token", "action": "login"}'
            ], 400);
        }
        
        // Direct verification to see raw response
        $secretKey = config('services.recaptcha.secret_key');
        
        $response = Http::timeout(10)->asForm()->post('https://www.google.com/recaptcha/api/siteverify', [
            'secret' => $secretKey,
            'response' => $token,
            'remoteip' => $request->ip(),
        ]);
        
        $rawResult = $response->json();
        
        // Also use service
        $service = app(CaptchaVerificationService::class);
        $serviceResult = $service->verify($token, $action, 'test@example.com');
        
        return response()->json([
            'token_info' => [
                'length' => strlen($token),
                'preview' => substr($token, 0, 30) . '...' . substr($token, -20),
            ],
            'google_raw_response' => $rawResult,
            'service_result' => $serviceResult,
            'interpretation' => [
                'success' => $rawResult['success'] ?? false,
                'score' => $rawResult['score'] ?? 'NOT PROVIDED (indicates invalid token)',
                'action' => $rawResult['action'] ?? 'NOT PROVIDED',
                'error_codes' => $rawResult['error-codes'] ?? [],
                'error_meaning' => interpretCaptchaErrorCodes($rawResult['error-codes'] ?? []),
            ],
        ]);
    })->name('captcha.debug.verify');

    /**
     * Get a test page to generate captcha token
     */
    Route::get('/test-page', function () {
        $siteKey = config('services.recaptcha.site_key');
        
        return response()->view('debug.captcha-test', [
            'siteKey' => $siteKey,
        ]);
    });
});

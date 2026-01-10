<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google reCAPTCHA v3 Configuration
    |--------------------------------------------------------------------------
    |
    | Configure your Google reCAPTCHA v3 credentials here.
    | You can obtain keys from: https://www.google.com/recaptcha/admin
    |
    | The captcha is applied ONLY to peserta (participant) role for:
    | - Login
    | - Forgot Password
    | - OTP Verification
    |
    | Admin and Pembimbing roles are excluded from captcha verification.
    |
    */
    'recaptcha' => [
        'enabled' => env('RECAPTCHA_ENABLED', true),
        'site_key' => env('RECAPTCHA_SITE_KEY', ''),
        'secret_key' => env('RECAPTCHA_SECRET_KEY', ''),
        'score_threshold' => env('RECAPTCHA_SCORE_THRESHOLD', 0.5),
    ],
];

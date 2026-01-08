<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Two-Factor Authentication Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all configuration options for the two-factor
    | authentication system including OTP settings, trusted devices,
    | and role-based policies.
    |
    */

    /*
    |--------------------------------------------------------------------------
    | OTP Configuration
    |--------------------------------------------------------------------------
    */

    'otp' => [
        // Number of digits in OTP code
        'length' => env('2FA_OTP_LENGTH', 6),
        
        // OTP expiration time in minutes
        'expires_in_minutes' => env('2FA_OTP_EXPIRES_MINUTES', 5),
        
        // Maximum verification attempts per OTP
        'max_attempts' => env('2FA_OTP_MAX_ATTEMPTS', 5),
        
        // Cooldown between resend requests in seconds
        'resend_cooldown_seconds' => env('2FA_OTP_RESEND_COOLDOWN', 60),
    ],

    /*
    |--------------------------------------------------------------------------
    | Trusted Device Configuration
    |--------------------------------------------------------------------------
    */

    'trusted_device' => [
        // Enable trusted device feature
        'enabled' => env('2FA_TRUSTED_DEVICE_ENABLED', true),
        
        // Trust duration in days
        'duration_days' => env('2FA_TRUSTED_DEVICE_DAYS', 30),
        
        // Cookie name for trusted device token
        'cookie_name' => env('2FA_TRUSTED_DEVICE_COOKIE', 'trusted_device_token'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Role-Based Policy
    |--------------------------------------------------------------------------
    |
    | Define which roles require two-factor authentication and which can
    | use trusted devices to bypass repeated OTP verification.
    |
    */

    'roles' => [
        // Roles that require two-factor authentication
        'require_2fa' => ['admin', 'pembimbing'],
        
        // Roles that can use trusted devices (subset of require_2fa)
        'allow_trusted_devices' => ['admin', 'pembimbing'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Queue Configuration
    |--------------------------------------------------------------------------
    */

    'queue' => [
        // Queue name for OTP emails (use 'high' for priority)
        'name' => env('2FA_QUEUE_NAME', 'high'),
        
        // Number of retry attempts for failed email jobs
        'tries' => env('2FA_QUEUE_TRIES', 3),
        
        // Backoff in seconds between retries
        'backoff' => env('2FA_QUEUE_BACKOFF', 10),
    ],

    /*
    |--------------------------------------------------------------------------
    | Audit Logging
    |--------------------------------------------------------------------------
    */

    'audit' => [
        // Enable audit logging
        'enabled' => env('2FA_AUDIT_ENABLED', true),
        
        // Retention period for audit logs in days (null = never delete)
        'retention_days' => env('2FA_AUDIT_RETENTION_DAYS', 90),
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Settings
    |--------------------------------------------------------------------------
    */

    'security' => [
        // Revoke trusted devices on password change
        'revoke_on_password_change' => true,
        
        // Revoke trusted devices on email change
        'revoke_on_email_change' => true,
        
        // Include partial IP hash in device fingerprint
        'include_ip_in_fingerprint' => false,
    ],

];

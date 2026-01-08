<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->web(append: [
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
        ]);

        // CRITICAL: HandleCSRFErrors must come before HandleInertiaRequests
        // This allows CSRF errors to be caught and handled gracefully
        $middleware->web(append: [
            \App\Http\Middleware\HandleCSRFErrors::class,
            \App\Http\Middleware\HandleInertiaRequests::class,
            \App\Http\Middleware\ShareUserAcceptanceStatus::class,
        ]);

        // Add Sanctum middleware for API authentication
        $middleware->api(prepend: [
            \Laravel\Sanctum\Http\Middleware\EnsureFrontendRequestsAreStateful::class,
        ]);

        // Register role middleware
        $middleware->alias([
            'role' => \App\Http\Middleware\CheckRole::class,
            '2fa' => \App\Http\Middleware\EnsureTwoFactorVerified::class,
        ]);
        
        // Configure trusted proxies for production (load balancers, CloudFlare, etc.)
        $middleware->trustProxies(at: [
            '*', // Trust all proxies in production
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        // Custom 419 CSRF error handling
        $exceptions->render(function (\Illuminate\Session\TokenMismatchException $e, $request) {
            if ($request->expectsJson() || $request->header('X-Inertia')) {
                return response()->json([
                    'message' => 'CSRF token mismatch. Please refresh and try again.',
                    'csrf_token' => csrf_token(),
                ], 419);
            }
            
            return redirect()->back()
                ->withInput($request->except(['password', 'password_confirmation']))
                ->withErrors(['csrf' => 'Your session has expired. Please try again.']);
        });
    })->create();

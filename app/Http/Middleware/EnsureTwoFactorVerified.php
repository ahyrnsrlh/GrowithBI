<?php

namespace App\Http\Middleware;

use App\Services\TwoFactorService;
use App\Services\TrustedDeviceService;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

/**
 * EnsureTwoFactorVerified Middleware
 * 
 * Ensures that users requiring two-factor authentication have completed
 * the verification process before accessing protected routes.
 * 
 * Security flow:
 * 1. Check if user is authenticated
 * 2. Check if user's role requires 2FA
 * 3. Check if device is trusted (bypass 2FA)
 * 4. Check if 2FA was verified in current session
 * 5. If not verified, redirect to 2FA challenge
 */
class EnsureTwoFactorVerified
{
    protected TwoFactorService $twoFactorService;
    protected TrustedDeviceService $trustedDeviceService;

    public function __construct(
        TwoFactorService $twoFactorService,
        TrustedDeviceService $trustedDeviceService
    ) {
        $this->twoFactorService = $twoFactorService;
        $this->trustedDeviceService = $trustedDeviceService;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = Auth::user();

        // Not authenticated - let other middleware handle it
        if (!$user) {
            return $next($request);
        }

        // Check if 2FA is required for this user
        if (!$this->twoFactorService->isTwoFactorRequired($user)) {
            return $next($request);
        }

        // Check if device is trusted (skip 2FA)
        if ($this->trustedDeviceService->isDeviceTrusted($user, $request)) {
            return $next($request);
        }

        // Check session flag for 2FA verification
        if ($request->session()->has('two_factor_verified') && 
            $request->session()->get('two_factor_verified') === true) {
            return $next($request);
        }

        // Check if this is a 2FA-related route (avoid redirect loop)
        if ($this->isTwoFactorRoute($request)) {
            return $next($request);
        }

        // User needs to complete 2FA - redirect to challenge
        if ($request->expectsJson() || $request->header('X-Inertia')) {
            return response()->json([
                'message' => 'Two-factor authentication required.',
                'two_factor_required' => true,
                'redirect' => route('two-factor.challenge'),
            ], 403);
        }

        return redirect()->route('two-factor.challenge');
    }

    /**
     * Check if the current request is for a 2FA-related route.
     */
    protected function isTwoFactorRoute(Request $request): bool
    {
        $twoFactorRoutes = [
            'two-factor.challenge',
            'two-factor.verify',
            'two-factor.resend',
            'logout',
        ];

        $currentRoute = $request->route()?->getName();

        return in_array($currentRoute, $twoFactorRoutes);
    }
}

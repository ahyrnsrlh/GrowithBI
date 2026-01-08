<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Services\TwoFactorService;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Inertia\Response;

/**
 * AuthenticatedSessionController
 * 
 * Handles user authentication with integrated two-factor authentication support.
 * 
 * Authentication flow:
 * 1. User submits credentials
 * 2. Validate credentials
 * 3. Check if 2FA is required for user's role
 * 4. Check if device is trusted (skip 2FA)
 * 5. If 2FA required, send OTP and redirect to challenge
 * 6. Otherwise, complete login
 */
class AuthenticatedSessionController extends Controller
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
     * Display the login view.
     */
    public function create(Request $request): Response
    {
        return Inertia::render('Auth/Login', [
            'canResetPassword' => Route::has('password.request'),
            'status' => session('status'),
            'success' => session('success'),
        ]);
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        $user = Auth::user();

        // Check if user is active
        if (!$user->is_active) {
            Auth::logout();
            $request->session()->invalidate();
            return redirect()->route('login')->withErrors([
                'email' => 'Akun Anda tidak aktif. Silakan hubungi administrator.',
            ]);
        }

        // Check if two-factor authentication is required
        if ($this->twoFactorService->isTwoFactorRequired($user)) {
            // Check if device is trusted
            if ($this->trustedDeviceService->isDeviceTrusted($user, $request)) {
                // Device is trusted, skip 2FA
                $request->session()->put('two_factor_verified', true);
                return $this->redirectBasedOnRole($user)
                    ->with('success', 'Selamat datang kembali!');
            }

            // OTP will be sent in TwoFactorController::create() via ensureActiveOtpExists()
            // This prevents duplicate OTP generation

            // Redirect to 2FA challenge
            return redirect()->route('two-factor.challenge')
                ->with('status', 'Kode verifikasi telah dikirim ke email Anda.');
        }

        // No 2FA required, complete login
        return $this->redirectBasedOnRole($user);
    }

    /**
     * Redirect user based on their role after login
     */
    private function redirectBasedOnRole($user): RedirectResponse
    {
        switch ($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'pembimbing':
                return redirect()->route('profile.edit');
            case 'peserta':
                return redirect()->route('profile.edit');
            default:
                return redirect()->intended(route('dashboard', absolute: false));
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        // Clear 2FA session flag
        $request->session()->forget('two_factor_verified');

        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        // Return response with forget cookie for trusted device (optional logout from device)
        return redirect()->route('login');
    }
}

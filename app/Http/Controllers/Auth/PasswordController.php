<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TrustedDeviceService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

/**
 * PasswordController
 * 
 * Handles password updates with security considerations:
 * - Validates current password before allowing change
 * - Revokes all trusted devices on password change
 */
class PasswordController extends Controller
{
    protected TrustedDeviceService $trustedDeviceService;

    public function __construct(TrustedDeviceService $trustedDeviceService)
    {
        $this->trustedDeviceService = $trustedDeviceService;
    }

    /**
     * Update the user's password.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $user = $request->user();

        $user->update([
            'password' => Hash::make($validated['password']),
        ]);

        // Security: Revoke all trusted devices on password change
        $this->trustedDeviceService->onPasswordChanged($user, $request);

        return back()->with('status', 'Password berhasil diperbarui. Semua perangkat terpercaya telah dicabut.');
    }
}

<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Services\TrustedDeviceService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * TrustedDeviceController
 * 
 * Handles trusted device management from user profile:
 * - List trusted devices
 * - Revoke specific devices
 * - Revoke all devices
 */
class TrustedDeviceController extends Controller
{
    protected TrustedDeviceService $trustedDeviceService;

    public function __construct(TrustedDeviceService $trustedDeviceService)
    {
        $this->trustedDeviceService = $trustedDeviceService;
    }

    /**
     * Get list of trusted devices for the authenticated user.
     */
    public function index(Request $request)
    {
        $user = Auth::user();

        if (!$this->trustedDeviceService->isTrustedDeviceEnabled($user)) {
            return response()->json([
                'message' => 'Trusted devices are not available for your role.',
                'devices' => [],
            ]);
        }

        $devices = $this->trustedDeviceService->getTrustedDevices($user);

        return response()->json([
            'devices' => $devices,
        ]);
    }

    /**
     * Revoke a specific trusted device.
     */
    public function destroy(Request $request, int $deviceId)
    {
        $user = Auth::user();

        $result = $this->trustedDeviceService->revokeDevice($user, $deviceId, $request);

        if (!$result) {
            return response()->json([
                'success' => false,
                'message' => 'Perangkat tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Perangkat berhasil dihapus dari daftar perangkat terpercaya.',
        ]);
    }

    /**
     * Revoke all trusted devices.
     */
    public function destroyAll(Request $request)
    {
        $user = Auth::user();

        $count = $this->trustedDeviceService->revokeAllDevices($user, $request, 'manual_logout_all');

        // Clear the current device cookie
        $forgetCookie = $this->trustedDeviceService->getForgetCookie();

        return response()->json([
            'success' => true,
            'message' => "{$count} perangkat telah dihapus dari daftar perangkat terpercaya.",
            'devices_revoked' => $count,
        ])->withCookie($forgetCookie);
    }
}

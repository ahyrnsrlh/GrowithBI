<?php

namespace App\Http\Controllers\Peserta;

use App\Events\AttendanceCreated;
use App\Events\AttendanceUpdated;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Notifications\AttendanceNotification;
use App\Services\FaceRecognitionService;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    // PT Tunas Dwipa Matra Pramuka - Bandar Lampung coordinates
    const OFFICE_LATITUDE  = -5.397140;
    const OFFICE_LONGITUDE = 105.266792;
    const ALLOWED_RADIUS   = 50000; // 50 km — for testing (can attend from anywhere)

    public function __construct(
        private readonly FaceRecognitionService $faceService
    ) {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            /** @var User|null $user */
            $user = $request->user();

            if (!$user || !$user->canAccessAttendance()) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Anda harus memiliki status aplikasi "Diterima" untuk mengakses fitur absensi.');
            }
            return $next($request);
        });
    }

    /**
     * Display attendance page — redirects to Profile with attendance tab for SPA experience
     */
    public function index(): Response
    {
        /** @var User $user */
        $user = Auth::user();

        $today       = Carbon::now('Asia/Jakarta')->toDateString();
        $attendances = $user->attendances()->orderByDesc('date')->get();

        return Inertia::render('Peserta/Attendance/Index', [
            'attendances'     => $attendances,
            'attendanceHistory' => $attendances,
            'todayAttendance' => $user->attendances()->where('date', $today)->first(),
            'stats'           => $user->getAttendanceStats(),
            'officeLocation'  => [
                'latitude'  => self::OFFICE_LATITUDE,
                'longitude' => self::OFFICE_LONGITUDE,
            ],
            'currentDateTime' => Carbon::now('Asia/Jakarta')->toISOString(),
            'face_enrolled'   => !empty($user->face_descriptor),
        ]);
    }

    // =========================================================================
    // FACE ENROLLMENT
    // =========================================================================

    /**
     * Return face enrollment status for the authenticated user.
     */
    public function faceStatus(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        return response()->json([
            'enrolled'            => !empty($user->face_descriptor),
            'face_registered_at'  => $user->face_registered_at?->toISOString(),
        ]);
    }

    /**
     * Enroll the authenticated user's face from multiple descriptor samples.
     * Accepts 1–5 samples, averaged server-side for better accuracy.
     */
    public function enrollFace(Request $request): JsonResponse
    {
        $request->validate([
            'descriptors'   => 'required|array|min:1|max:5',
            'descriptors.*' => 'required|array|size:128',
        ]);

        /** @var User $user */
        $user = Auth::user();

        try {
            $this->faceService->enrollFace($user, $request->descriptors);
        } catch (\Exception $e) {
            Log::error('Face enrollment failed', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Pendaftaran wajah gagal. Silakan coba lagi.',
            ], 500);
        }

        return response()->json([
            'success'            => true,
            'message'            => 'Wajah berhasil didaftarkan!',
            'face_registered_at' => $user->fresh()->face_registered_at?->toISOString(),
        ]);
    }

    // =========================================================================
    // CHECK-IN
    // =========================================================================

    /**
     * Handle check-in
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'photo'           => 'required|string', // Base64 encoded image
            'face_descriptor' => 'required|array|size:128',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        // IMPORTANT: Always use server time, never trust client time
        $now   = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // 1. FACE VERIFICATION — reject if not enrolled
        $faceResult = $this->faceService->verifyFace($user, $request->face_descriptor);
        if (!$faceResult['success']) {
            if (!empty($user->face_descriptor)) {
                $this->sendFailedAttendanceNotification($user, 'face_not_recognized');
            }
            return redirect()->back()->with('error', $faceResult['message']);
        }

        // Validate check-in time range (07:30 - 08:00 WIB)
        // DISABLED FOR TESTING — Uncomment for production
        /*
        $checkInStart = Carbon::today('Asia/Jakarta')->setTime(7, 30, 0);
        $checkInEnd   = Carbon::today('Asia/Jakarta')->setTime(8, 0, 0);
        if ($now->lt($checkInStart) || $now->gt($checkInEnd)) {
            return redirect()->back()->with('error',
                'Check-in hanya diperbolehkan antara pukul 07:30 - 08:00 WIB. '
                . 'Waktu server saat ini: ' . $now->format('H:i:s') . ' WIB'
            );
        }
        */

        // Check if already checked in today
        $existingAttendance = $user->attendances()->where('date', $today)->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Validate location
        if (!$this->isWithinAllowedLocation($request->latitude, $request->longitude)) {
            $this->sendFailedAttendanceNotification($user, 'location_invalid');
            return redirect()->back()->with('error', 'Absensi hanya dapat dilakukan di lokasi magang Bank Indonesia.');
        }

        // Save photo
        $photoPath = $this->savePhoto($request->photo, 'checkin', $user->id);

        // Determine status based on server time
        $officeStartTime = Carbon::today('Asia/Jakarta')->setTime(8, 0, 0);
        $status          = $now->isAfter($officeStartTime) ? 'Late' : 'On-Time';

        // Create or update attendance — ALWAYS use server time
        $attendance                   = $existingAttendance ?: new Attendance();
        $attendance->user_id          = $user->id;
        $attendance->date             = $today;
        $attendance->check_in         = $now;
        $attendance->status           = $status;
        $attendance->latitude         = $request->latitude;
        $attendance->longitude        = $request->longitude;
        $attendance->location_address = $this->getLocationAddress($request->latitude, $request->longitude);
        $attendance->photo_checkin    = $photoPath;

        try {
            $attendance->save();
            Log::info('Attendance check-in saved', [
                'user_id'      => $user->id,
                'attendance_id'=> $attendance->id,
                'date'         => $today,
                'check_in'     => $now->format('Y-m-d H:i:s'),
                'status'       => $status,
                'confidence'   => $faceResult['confidence'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save attendance', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Gagal menyimpan data absensi. Silakan coba lagi.');
        }

        // Broadcast real-time insert to admin table
        event(new AttendanceCreated($attendance));

        // Send notifications
        $notificationType = $status === 'On-Time' ? 'checked_in' : 'late';
        $user->notify(new AttendanceNotification($attendance, $notificationType));
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            /** @var User $admin */
            $admin->notify(new AttendanceNotification($attendance, $notificationType));
        }

        $message = $status === 'On-Time'
            ? 'Check-in berhasil pada ' . $now->format('H:i:s') . ' WIB! Anda tepat waktu.'
            : 'Check-in berhasil pada ' . $now->format('H:i:s') . ' WIB! Anda terlambat.';

        return redirect()->back()->with('success', $message);
    }

    // =========================================================================
    // CHECK-OUT
    // =========================================================================

    /**
     * Handle check-out
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude'        => 'required|numeric',
            'longitude'       => 'required|numeric',
            'photo'           => 'required|string',
            'face_descriptor' => 'required|array|size:128',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        $now   = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // 1. FACE VERIFICATION — reject if not enrolled or no match
        $faceResult = $this->faceService->verifyFace($user, $request->face_descriptor);
        if (!$faceResult['success']) {
            $this->sendFailedAttendanceNotification($user, 'face_not_recognized');
            return redirect()->back()->with('error', $faceResult['message']);
        }

        $attendance = $user->attendances()->where('date', $today)->first();

        if (!$attendance || !$attendance->check_in) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // Validate check-out time range (16:00 - 18:00 WIB)
        // DISABLED FOR TESTING — Uncomment for production
        /*
        $checkOutStart = Carbon::today('Asia/Jakarta')->setTime(16, 0, 0);
        $checkOutEnd   = Carbon::today('Asia/Jakarta')->setTime(18, 0, 0);
        if ($now->lt($checkOutStart) || $now->gt($checkOutEnd)) {
            return redirect()->back()->with('error',
                'Check-out hanya diperbolehkan antara pukul 16:00 - 18:00 WIB. '
                . 'Waktu server saat ini: ' . $now->format('H:i:s') . ' WIB'
            );
        }
        */

        // Validate location
        if (!$this->isWithinAllowedLocation($request->latitude, $request->longitude)) {
            $this->sendFailedAttendanceNotification($user, 'location_invalid');
            return redirect()->back()->with('error', 'Check-out hanya dapat dilakukan di lokasi magang Bank Indonesia.');
        }

        // Save photo
        $photoPath = $this->savePhoto($request->photo, 'checkout', $user->id);

        // Update attendance — ALWAYS use server time
        $attendance->check_out      = $now;
        $attendance->photo_checkout = $photoPath;

        try {
            $attendance->save();
            Log::info('Attendance check-out saved', [
                'user_id'      => $user->id,
                'attendance_id'=> $attendance->id,
                'date'         => $today,
                'check_out'    => $now->format('Y-m-d H:i:s'),
                'confidence'   => $faceResult['confidence'],
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to save check-out', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Gagal menyimpan data check-out. Silakan coba lagi.');
        }

        // Broadcast real-time update to admin table
        event(new AttendanceUpdated($attendance));

        // Send notifications
        $user->notify(new AttendanceNotification($attendance, 'checked_out'));
        $admins = User::where('role', 'admin')->get();
        foreach ($admins as $admin) {
            /** @var User $admin */
            $admin->notify(new AttendanceNotification($attendance, 'checked_out'));
        }

        return redirect()->back()->with(
            'success',
            'Check-out berhasil pada ' . $now->format('H:i:s') . ' WIB! Terima kasih atas kerja keras Anda hari ini.'
        );
    }

    // =========================================================================
    // STATS
    // =========================================================================

    /**
     * Get attendance statistics for the authenticated user
     */
    public function stats(): JsonResponse
    {
        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }

        return response()->json($user->getAttendanceStats());
    }

    // =========================================================================
    // PRIVATE HELPERS
    // =========================================================================

    /**
     * Check if coordinates are within allowed location
     */
    private function isWithinAllowedLocation($latitude, $longitude): bool
    {
        $distance = $this->calculateDistance(
            self::OFFICE_LATITUDE,
            self::OFFICE_LONGITUDE,
            $latitude,
            $longitude
        );

        return $distance <= self::ALLOWED_RADIUS;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius  = 6371000; // metres
        $lat1Rad      = deg2rad($lat1);
        $lat2Rad      = deg2rad($lat2);
        $deltaLatRad  = deg2rad($lat2 - $lat1);
        $deltaLonRad  = deg2rad($lon2 - $lon1);

        $a = sin($deltaLatRad / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLonRad / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get location address from coordinates (placeholder)
     */
    private function getLocationAddress($latitude, $longitude): string
    {
        return "Bank Indonesia KPw Lampung ({$latitude}, {$longitude})";
    }

    /**
     * Save photo from base64 string
     */
    private function savePhoto(string $base64Photo, string $type, int $userId): string
    {
        $image    = preg_replace('/^data:image\/\w+;base64,/', '', $base64Photo);
        $image    = base64_decode($image);
        $filename = $type . '_' . $userId . '_' . Carbon::now()->format('YmdHis') . '.jpg';
        $path     = 'attendance_photos/' . Carbon::now()->format('Y/m/d') . '/' . $filename;

        Storage::disk('public')->put($path, $image);

        return $path;
    }

    /**
     * Send notification for failed attendance attempt
     */
    private function sendFailedAttendanceNotification(User $user, string $type): void
    {
        try {
            $attendance          = new Attendance();
            $attendance->user_id = $user->id;
            $attendance->date    = Carbon::now('Asia/Jakarta')->toDateString();
            $attendance->forceFill([
                'user_id' => $user->id,
                'date'    => Carbon::now('Asia/Jakarta')->toDateString(),
            ]);

            $user->notify(new AttendanceNotification($attendance, $type));

            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                /** @var User $admin */
                $admin->notify(new AttendanceNotification($attendance, $type));
            }

            Log::info('Failed attendance notification sent', [
                'user_id'   => $user->id,
                'type'      => $type,
                'timestamp' => now(),
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send attendance notification', [
                'user_id' => $user->id,
                'type'    => $type,
                'error'   => $e->getMessage(),
            ]);
        }
    }
}

<?php

namespace App\Http\Controllers\Peserta;

use App\Events\AttendanceCreated;
use App\Events\AttendanceUpdated;
use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Notifications\AttendanceNotification;
use App\Services\FaceRecognitionService;
use App\Services\LocationValidationService;
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
    const OFFICE_LATITUDE  = -5.4194538;
    const OFFICE_LONGITUDE = 105.2604370;
    const ALLOWED_RADIUS   = 50000; // 50 km — for testing (can attend from anywhere)

    public function __construct(
        private readonly FaceRecognitionService $faceService,
        private readonly LocationValidationService $locationService
    ) {
        $this->middleware('auth');

        // Guard 1: Require accepted application
        $this->middleware(function ($request, $next) {
            /** @var User|null $user */
            $user = $request->user();

            if (!$user || !$user->canAccessAttendance()) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Anda harus memiliki status aplikasi "Diterima" untuk mengakses fitur absensi.');
            }
            return $next($request);
        });

        // Guard 2: Require face enrollment (biometric registration)
        // Exempt enrollFace and faceStatus so users can complete enrollment first.
        $this->middleware(function ($request, $next) {
            /** @var User|null $user */
            $user = $request->user();

            if (!$user || !$user->hasFaceEnrolled()) {
                if ($request->wantsJson() || $request->expectsJson()) {
                    return response()->json([
                        'success'  => false,
                        'message'  => 'Registrasi wajah belum selesai. Silakan lengkapi foto profil terlebih dahulu.',
                        'redirect' => route('profile.edit'),
                    ], 403);
                }
                return redirect()->route('profile.edit')
                    ->with('error', 'Anda harus menyelesaikan registrasi foto profil terlebih dahulu sebelum menggunakan fitur absensi.');
            }
            return $next($request);
        })->except(['enrollFace', 'faceStatus']);
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
                'latitude'  => (float)config('attendance.office.latitude', -5.4194538),
                'longitude' => (float)config('attendance.office.longitude', 105.2604370),
            ],
            'allowedRadius'   => (int)config('attendance.radius', 500),
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

    /**
     * Verify the authenticated user's face against their enrolled descriptor.
     */
    public function verifyFace(Request $request): JsonResponse
    {
        $request->validate([
            'face_descriptor' => 'required|array|size:128',
            'photo'           => 'nullable|string',
        ]);

        /** @var User $user */
        $user = Auth::user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized.',
            ], 401);
        }

        $faceResult = $this->faceService->verifyFace($user, $request->face_descriptor);

        if ($faceResult['success']) {
            // Save face verification status + timestamp in session (expires in 5 minutes)
            session()->put('face_verified_at', now()->timestamp);
            session()->put('face_verified_user_id', $user->id);

            return response()->json([
                'success' => true,
                'message' => 'Verifikasi wajah berhasil.',
            ]);
        }

        // Log failed attempt to logs
        Log::warning('Face verification failed for user during pre-submission scan', [
            'user_id' => $user->id,
            'error'   => $faceResult['message'],
        ]);

        if (!empty($user->face_descriptor)) {
            $this->sendFailedAttendanceNotification($user, 'face_not_recognized');
        }

        return response()->json([
            'success' => false,
            'message' => 'Wajah yang terdeteksi tidak sesuai dengan wajah yang telah didaftarkan. Pastikan Anda menggunakan akun dan wajah Anda sendiri untuk melakukan presensi.',
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
            'latitude'             => 'required|numeric',
            'longitude'            => 'required|numeric',
            'photo'                => 'required|string', // Base64 encoded image
            'face_descriptor'      => 'required|array|size:128',
            'gps_accuracy'         => 'nullable|numeric',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        // Enforce face verification session check
        $verifiedAt = session()->get('face_verified_at');
        $verifiedUserId = session()->get('face_verified_user_id');
        if (!$verifiedAt || $verifiedUserId !== $user->id || (now()->timestamp - $verifiedAt) > 300) {
            return redirect()->back()
                ->withErrors(['error' => 'Verifikasi wajah diperlukan sebelum melakukan presensi.'])
                ->with('error', 'Verifikasi wajah diperlukan sebelum melakukan presensi.');
        }

        // IMPORTANT: Always use server time, never trust client time
        $now   = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // Check if already checked in today
        $existingAttendance = $user->attendances()->where('date', $today)->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return redirect()->back()
                ->withErrors(['error' => 'Anda sudah melakukan check-in hari ini.'])
                ->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // 2. LOCATION VALIDATION (Radius-Based)
        $locationResult = $this->locationService->validateRadius(
            (float) $request->latitude,
            (float) $request->longitude,
            $request->gps_accuracy ? (float) $request->gps_accuracy : null
        );

        if (!$locationResult['passed']) {
            $this->sendFailedFailedAttendanceNotification($user);
            Log::warning('Attendance check-in location validation failed', [
                'user_id' => $user->id,
                'location_notes' => $locationResult['notes'],
                'gps_accuracy' => $request->gps_accuracy,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return redirect()->back()
                ->withErrors(['error' => $locationResult['notes'] ?: 'Akses lokasi tidak valid.'])
                ->with('error', $locationResult['notes'] ?: 'Akses lokasi tidak valid.');
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
 
        // Store location validation metadata
        $attendance->gps_accuracy               = $request->gps_accuracy;
        $attendance->coordinate_stability       = null;
        $attendance->location_validation_status  = 'passed';
        $attendance->location_validation_notes   = $locationResult['notes'] ?? null;
        $attendance->suspicious_location         = false;
        $attendance->validation_timestamp       = Carbon::now('Asia/Jakarta');
 
        try {
            $attendance->save();
            Log::info('Attendance check-in saved', [
                'user_id'      => $user->id,
                'attendance_id'=> $attendance->id,
                'date'         => $today,
                'check_in'     => $now->format('Y-m-d H:i:s'),
                'status'       => $status,
                'gps_accuracy' => $attendance->gps_accuracy,
            ]);
            // Clear face verification status from session
            session()->forget(['face_verified_at', 'face_verified_user_id']);
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

    /**
     * Send failed attendance notification due to location validation (helper to avoid duplicate sendFailedAttendanceNotification call)
     */
    private function sendFailedFailedAttendanceNotification(User $user)
    {
        try {
            $this->sendFailedAttendanceNotification($user, 'location_invalid');
        } catch (\Exception $e) {
            Log::error('Failed to send location invalid notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);
        }
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
            'latitude'             => 'required|numeric',
            'longitude'            => 'required|numeric',
            'photo'                => 'required|string',
            'face_descriptor'      => 'required|array|size:128',
            'gps_accuracy'         => 'nullable|numeric',
        ]);

        /** @var User $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }

        // Enforce face verification session check
        $verifiedAt = session()->get('face_verified_at');
        $verifiedUserId = session()->get('face_verified_user_id');
        if (!$verifiedAt || $verifiedUserId !== $user->id || (now()->timestamp - $verifiedAt) > 300) {
            return redirect()->back()
                ->withErrors(['error' => 'Verifikasi wajah diperlukan sebelum melakukan presensi.'])
                ->with('error', 'Verifikasi wajah diperlukan sebelum melakukan presensi.');
        }

        $now   = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        $attendance = $user->attendances()->where('date', $today)->first();

        if (!$attendance || !$attendance->check_in) {
            return redirect()->back()
                ->withErrors(['error' => 'Anda belum melakukan check-in hari ini.'])
                ->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out) {
            return redirect()->back()
                ->withErrors(['error' => 'Anda sudah melakukan check-out hari ini.'])
                ->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // 2. LOCATION VALIDATION (Radius-Based)
        $locationResult = $this->locationService->validateRadius(
            (float) $request->latitude,
            (float) $request->longitude,
            $request->gps_accuracy ? (float) $request->gps_accuracy : null
        );

        if (!$locationResult['passed']) {
            $this->sendFailedFailedAttendanceNotification($user);
            Log::warning('Attendance check-out location validation failed', [
                'user_id' => $user->id,
                'location_notes' => $locationResult['notes'],
                'gps_accuracy' => $request->gps_accuracy,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);
            return redirect()->back()
                ->withErrors(['error' => $locationResult['notes'] ?: 'Akses lokasi tidak valid.'])
                ->with('error', $locationResult['notes'] ?: 'Akses lokasi tidak valid.');
        }

        // Save photo
        $photoPath = $this->savePhoto($request->photo, 'checkout', $user->id);

        // Update attendance — ALWAYS use server time
        $attendance->check_out          = $now;
        $attendance->photo_checkout     = $photoPath;
        $attendance->checkout_latitude  = $request->latitude;
        $attendance->checkout_longitude = $request->longitude;
 
        // Simplify metadata storage on checkout
        $attendance->suspicious_location = false;
        $attendance->location_validation_status = 'passed';
        if (!empty($locationResult['notes'])) {
            $attendance->location_validation_notes = $attendance->location_validation_notes
                ? $attendance->location_validation_notes . '; ' . $locationResult['notes']
                : $locationResult['notes'];
        }

        try {
            $attendance->save();
            Log::info('Attendance check-out saved', [
                'user_id'      => $user->id,
                'attendance_id'=> $attendance->id,
                'date'         => $today,
                'check_out'    => $now->format('Y-m-d H:i:s'),
            ]);
            // Clear face verification status from session
            session()->forget(['face_verified_at', 'face_verified_user_id']);
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

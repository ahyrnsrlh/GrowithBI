<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Notifications\AttendanceCheckedIn;
use App\Notifications\AttendanceCheckedOut;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    // PT Tunas Dwipa Matra Pramuka - Bandar Lampung coordinates
    const OFFICE_LATITUDE = -5.397140;
    const OFFICE_LONGITUDE = 105.266792;
    const ALLOWED_RADIUS = 50000; // 50km - untuk testing (bisa absen dari mana saja)

    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (!Auth::user()->canAccessAttendance()) {
                return redirect()->route('profile.edit')
                    ->with('error', 'Anda harus memiliki status aplikasi "Diterima" untuk mengakses fitur absensi.');
            }
            return $next($request);
        });
    }

    /**
     * Display attendance page
     */
    public function index(): Response
    {
        $user = Auth::user();
        $today = Carbon::now()->toDateString();
        
        // Get today's attendance
        $todayAttendance = $user->attendances()
            ->where('date', $today)
            ->first();

        // Get attendance history (last 30 days)
        $attendanceHistory = $user->attendances()
            ->where('date', '>=', Carbon::now()->subDays(30))
            ->orderBy('date', 'desc')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date->format('Y-m-d'),
                    'date_formatted' => $attendance->date->format('d M Y'),
                    'check_in' => $attendance->check_in ? $attendance->check_in->format('Y-m-d H:i:s') : null,
                    'check_out' => $attendance->check_out ? $attendance->check_out->format('Y-m-d H:i:s') : null,
                    'status' => $attendance->status,
                    'working_duration' => $attendance->getWorkingDuration(),
                    'is_complete' => $attendance->isComplete(),
                ];
            });

        // Get attendance statistics for current month
        $stats = $user->getAttendanceStats();

        return Inertia::render('Peserta/Attendance/Index', [
            'todayAttendance' => $todayAttendance ? [
                'id' => $todayAttendance->id,
                'date' => $todayAttendance->date->format('Y-m-d'),
                'check_in' => $todayAttendance->check_in ? $todayAttendance->check_in->format('Y-m-d H:i:s') : null,
                'check_out' => $todayAttendance->check_out ? $todayAttendance->check_out->format('Y-m-d H:i:s') : null,
                'status' => $todayAttendance->status,
                'can_check_in' => !$todayAttendance->check_in,
                'can_check_out' => $todayAttendance->check_in && !$todayAttendance->check_out,
                'photo_checkin_url' => $todayAttendance->photo_checkin_url,
                'photo_checkout_url' => $todayAttendance->photo_checkout_url,
            ] : null,
            'attendanceHistory' => $attendanceHistory,
            'stats' => $stats,
            'officeLocation' => [
                'latitude' => self::OFFICE_LATITUDE,
                'longitude' => self::OFFICE_LONGITUDE,
                'radius' => self::ALLOWED_RADIUS,
            ],
            'currentDateTime' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Handle check-in
     */
    public function checkIn(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|string', // Base64 encoded image
        ]);

        $user = Auth::user();
        
        // IMPORTANT: Always use server time, never trust client time
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        // Validate check-in time range (07:30 - 08:00 WIB)
        $checkInStart = Carbon::today('Asia/Jakarta')->setTime(7, 30, 0);
        $checkInEnd = Carbon::today('Asia/Jakarta')->setTime(8, 0, 0);
        
        if ($now->lt($checkInStart) || $now->gt($checkInEnd)) {
            return redirect()->back()->with('error', 
                'Check-in hanya diperbolehkan antara pukul 07:30 - 08:00 WIB. ' .
                'Waktu server saat ini: ' . $now->format('H:i:s') . ' WIB'
            );
        }

        // Check if already checked in today
        $existingAttendance = $user->attendances()
            ->where('date', $today)
            ->first();

        if ($existingAttendance && $existingAttendance->check_in) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-in hari ini.');
        }

        // Validate location
        if (!$this->isWithinAllowedLocation($request->latitude, $request->longitude)) {
            return redirect()->back()->with('error', 'Absensi hanya dapat dilakukan di lokasi magang Bank Indonesia.');
        }

        // Save photo
        $photoPath = $this->savePhoto($request->photo, 'checkin', $user->id);

        // Determine status based on server time
        $officeStartTime = Carbon::today('Asia/Jakarta')->setTime(8, 0, 0);
        $status = $now->isAfter($officeStartTime) ? 'Late' : 'On-Time';

        // Create or update attendance - ALWAYS use server time
        $attendance = $existingAttendance ?: new Attendance();
        $attendance->user_id = $user->id;
        $attendance->date = $today;
        $attendance->check_in = $now; // Server time only!
        $attendance->status = $status;
        $attendance->latitude = $request->latitude;
        $attendance->longitude = $request->longitude;
        $attendance->location_address = $this->getLocationAddress($request->latitude, $request->longitude);
        $attendance->photo_checkin = $photoPath;
        $attendance->save();

        // Fire event for real-time updates
        event(new \App\Events\AttendanceUpdated($attendance));

        // Send notification to user
        $user->notify(new AttendanceCheckedIn($attendance));

        $message = $status === 'On-Time' 
            ? 'Check-in berhasil pada ' . $now->format('H:i:s') . ' WIB! Anda tepat waktu.' 
            : 'Check-in berhasil pada ' . $now->format('H:i:s') . ' WIB! Anda terlambat.';

        return redirect()->back()->with('success', $message);
    }

    /**
     * Handle check-out
     */
    public function checkOut(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'photo' => 'required|string', // Base64 encoded image
        ]);

        $user = Auth::user();
        
        // IMPORTANT: Always use server time, never trust client time
        $now = Carbon::now('Asia/Jakarta');
        $today = $now->toDateString();

        $attendance = $user->attendances()
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // Validate check-out time range (16:00 - 18:00 WIB)
        $checkOutStart = Carbon::today('Asia/Jakarta')->setTime(16, 0, 0);
        $checkOutEnd = Carbon::today('Asia/Jakarta')->setTime(18, 0, 0);
        
        if ($now->lt($checkOutStart) || $now->gt($checkOutEnd)) {
            return redirect()->back()->with('error', 
                'Check-out hanya diperbolehkan antara pukul 16:00 - 18:00 WIB. ' .
                'Waktu server saat ini: ' . $now->format('H:i:s') . ' WIB'
            );
        }

        // Validate location
        if (!$this->isWithinAllowedLocation($request->latitude, $request->longitude)) {
            return redirect()->back()->with('error', 'Check-out hanya dapat dilakukan di lokasi magang Bank Indonesia.');
        }

        // Save photo
        $photoPath = $this->savePhoto($request->photo, 'checkout', $user->id);

        // Update attendance - ALWAYS use server time
        $attendance->check_out = $now; // Server time only!
        $attendance->photo_checkout = $photoPath;
        $attendance->save();

        // Fire event for real-time updates
        event(new \App\Events\AttendanceUpdated($attendance));

        // Send notification to user
        $user->notify(new AttendanceCheckedOut($attendance));

        return redirect()->back()->with('success', 
            'Check-out berhasil pada ' . $now->format('H:i:s') . ' WIB! Terima kasih atas kerja keras Anda hari ini.'
        );
    }

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
        $earthRadius = 6371000; // meters

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLatRad = deg2rad($lat2 - $lat1);
        $deltaLonRad = deg2rad($lon2 - $lon1);

        $a = sin($deltaLatRad / 2) ** 2 +
             cos($lat1Rad) * cos($lat2Rad) *
             sin($deltaLonRad / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }

    /**
     * Get location address from coordinates (placeholder)
     */
    private function getLocationAddress($latitude, $longitude): string
    {
        // In production, you would use Google Maps API or similar service
        return "Bank Indonesia KPw Lampung ({$latitude}, {$longitude})";
    }

    /**
     * Save photo from base64 string
     */
    private function savePhoto(string $base64Photo, string $type, int $userId): string
    {
        // Remove data:image/png;base64, prefix if exists
        $image = preg_replace('/^data:image\/\w+;base64,/', '', $base64Photo);
        $image = base64_decode($image);

        // Generate unique filename
        $filename = $type . '_' . $userId . '_' . Carbon::now()->format('YmdHis') . '.jpg';
        $path = 'attendance_photos/' . Carbon::now()->format('Y/m/d') . '/' . $filename;

        // Save to storage
        Storage::disk('public')->put($path, $image);

        return $path;
    }

    /**
     * Get attendance statistics
     */
    public function stats()
    {
        $user = Auth::user();
        $stats = $user->getAttendanceStats();
        
        return response()->json($stats);
    }
}

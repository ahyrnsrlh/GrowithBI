<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AttendanceController extends Controller
{
    // Bank Indonesia KPw Lampung office coordinates (example)
    const OFFICE_LATITUDE = -5.397140;  // Replace with actual coordinates
    const OFFICE_LONGITUDE = 105.266792; // Replace with actual coordinates
    const ALLOWED_RADIUS = 200; // meters

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
                    'check_in' => $attendance->check_in ? $attendance->check_in->format('H:i:s') : null,
                    'check_out' => $attendance->check_out ? $attendance->check_out->format('H:i:s') : null,
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
                'check_in' => $todayAttendance->check_in ? $todayAttendance->check_in->format('H:i:s') : null,
                'check_out' => $todayAttendance->check_out ? $todayAttendance->check_out->format('H:i:s') : null,
                'status' => $todayAttendance->status,
                'can_check_in' => !$todayAttendance->check_in,
                'can_check_out' => $todayAttendance->check_in && !$todayAttendance->check_out,
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
        ]);

        $user = Auth::user();
        $today = Carbon::now()->toDateString();

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

        $now = Carbon::now();
        $officeStartTime = Carbon::today()->setTime(8, 0, 0);
        $status = $now->isAfter($officeStartTime) ? 'Late' : 'On-Time';

        // Create or update attendance
        $attendance = $existingAttendance ?: new Attendance();
        $attendance->user_id = $user->id;
        $attendance->date = $today;
        $attendance->check_in = $now;
        $attendance->status = $status;
        $attendance->latitude = $request->latitude;
        $attendance->longitude = $request->longitude;
        $attendance->location_address = $this->getLocationAddress($request->latitude, $request->longitude);
        $attendance->save();

        // Fire event for real-time updates
        event(new \App\Events\AttendanceUpdated($attendance));

        $message = $status === 'On-Time' 
            ? 'Check-in berhasil! Anda tepat waktu.' 
            : 'Check-in berhasil! Anda terlambat.';

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
        ]);

        $user = Auth::user();
        $today = Carbon::now()->toDateString();

        $attendance = $user->attendances()
            ->where('date', $today)
            ->first();

        if (!$attendance || !$attendance->check_in) {
            return redirect()->back()->with('error', 'Anda belum melakukan check-in hari ini.');
        }

        if ($attendance->check_out) {
            return redirect()->back()->with('error', 'Anda sudah melakukan check-out hari ini.');
        }

        // Validate location
        if (!$this->isWithinAllowedLocation($request->latitude, $request->longitude)) {
            return redirect()->back()->with('error', 'Check-out hanya dapat dilakukan di lokasi magang Bank Indonesia.');
        }

        $attendance->check_out = Carbon::now();
        $attendance->save();

        // Fire event for real-time updates
        event(new \App\Events\AttendanceUpdated($attendance));

        return redirect()->back()->with('success', 'Check-out berhasil! Terima kasih atas kerja keras Anda hari ini.');
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
     * Get attendance statistics
     */
    public function stats()
    {
        $user = Auth::user();
        $stats = $user->getAttendanceStats();
        
        return response()->json($stats);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Attendance;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\AttendanceExport;

class AttendanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware(function ($request, $next) {
            if (auth()->user()->role !== 'admin') {
                abort(403);
            }
            return $next($request);
        });
    }

    /**
     * Display attendance management page
     */
    public function index(Request $request): Response
    {
        $query = Attendance::with(['user', 'user.division'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'peserta');
            });

        // Apply filters
        if ($request->date) {
            $query->where('date', $request->date);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }

        if ($request->division_id) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        if ($request->participant_id) {
            $query->where('user_id', $request->participant_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        if ($request->search) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->paginate(20)
            ->through(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'date' => $attendance->date->format('Y-m-d'),
                    'date_formatted' => $attendance->date->format('d M Y'),
                    'check_in' => $attendance->check_in ? $attendance->check_in->format('H:i:s') : null,
                    'check_out' => $attendance->check_out ? $attendance->check_out->format('H:i:s') : null,
                    'status' => $attendance->status,
                    'working_duration' => $attendance->getWorkingDuration(),
                    'user' => [
                        'id' => $attendance->user->id,
                        'name' => $attendance->user->name,
                        'email' => $attendance->user->email,
                        'division' => $attendance->user->division ? [
                            'id' => $attendance->user->division->id,
                            'name' => $attendance->user->division->name,
                        ] : null,
                    ],
                    'location' => [
                        'address' => $attendance->location_address,
                        'latitude' => $attendance->latitude,
                        'longitude' => $attendance->longitude,
                    ],
                    'photos' => [
                        'checkin' => $attendance->photo_checkin_url,
                        'checkout' => $attendance->photo_checkout_url,
                    ],
                ];
            });

        // Get filter options
        $divisions = Division::where('is_active', true)
            ->orderBy('name')
            ->get(['id', 'name']);

        $participants = User::where('role', 'peserta')
            ->where('is_active', true)
            ->whereHas('applications', function ($q) {
                $q->where('status', 'diterima');
            })
            ->with('division:id,name')
            ->orderBy('name')
            ->get(['id', 'name', 'division_id'])
            ->map(function ($user) {
                return [
                    'id' => $user->id,
                    'name' => $user->name,
                    'division' => $user->division ? $user->division->name : '-',
                ];
            });

        return Inertia::render('Admin/Attendance/Index', [
            'attendances' => $attendances,
            'divisions' => $divisions,
            'participants' => $participants,
            'filters' => [
                'date' => $request->date,
                'date_from' => $request->date_from,
                'date_to' => $request->date_to,
                'division_id' => $request->division_id,
                'participant_id' => $request->participant_id,
                'status' => $request->status,
                'search' => $request->search,
            ],
            'stats' => $this->getOverallStats($request),
        ]);
    }

    /**
     * Show attendance details
     */
    public function show(Attendance $attendance): Response
    {
        $attendance->load(['user', 'user.division']);

        return Inertia::render('Admin/Attendance/Show', [
            'attendance' => [
                'id' => $attendance->id,
                'date' => $attendance->date->format('Y-m-d'),
                'date_formatted' => $attendance->date->format('d M Y'),
                'check_in' => $attendance->check_in ? $attendance->check_in->format('H:i:s') : null,
                'check_out' => $attendance->check_out ? $attendance->check_out->format('H:i:s') : null,
                'status' => $attendance->status,
                'working_duration' => $attendance->getWorkingDuration(),
                'notes' => $attendance->notes,
                'user' => [
                    'id' => $attendance->user->id,
                    'name' => $attendance->user->name,
                    'email' => $attendance->user->email,
                    'division' => $attendance->user->division ? [
                        'id' => $attendance->user->division->id,
                        'name' => $attendance->user->division->name,
                    ] : null,
                ],
                'location' => [
                    'address' => $attendance->location_address,
                    'latitude' => $attendance->latitude,
                    'longitude' => $attendance->longitude,
                ],
            ],
        ]);
    }

    /**
     * Get attendance statistics
     */
    public function stats(Request $request)
    {
        $stats = $this->getOverallStats($request);
        return response()->json($stats);
    }

    /**
     * Export attendance data
     */
    public function export(Request $request)
    {
        $query = Attendance::with(['user', 'user.division'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'peserta');
            });

        // Apply same filters as index
        if ($request->date) {
            $query->where('date', $request->date);
        }

        if ($request->date_from && $request->date_to) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        }

        if ($request->division_id) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        if ($request->participant_id) {
            $query->where('user_id', $request->participant_id);
        }

        if ($request->status) {
            $query->where('status', $request->status);
        }

        $attendances = $query->orderBy('date', 'desc')
            ->orderBy('created_at', 'desc')
            ->get();

        $filename = 'attendance-report-' . Carbon::now()->format('Y-m-d-H-i-s') . '.xlsx';

        return Excel::download(new AttendanceExport($attendances), $filename);
    }

    /**
     * Get overall attendance statistics
     */
    private function getOverallStats(Request $request): array
    {
        $query = Attendance::whereHas('user', function ($q) {
            $q->where('role', 'peserta');
        });

        // Apply date filters
        if ($request->date) {
            $query->where('date', $request->date);
        } elseif ($request->date_from && $request->date_to) {
            $query->whereBetween('date', [$request->date_from, $request->date_to]);
        } else {
            // Default to current month
            $query->currentMonth();
        }

        // Apply other filters
        if ($request->division_id) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('division_id', $request->division_id);
            });
        }

        if ($request->participant_id) {
            $query->where('user_id', $request->participant_id);
        }

        $attendances = $query->get();

        $total = $attendances->count();
        $present = $attendances->whereIn('status', ['On-Time', 'Late'])->count();
        $onTime = $attendances->where('status', 'On-Time')->count();
        $late = $attendances->where('status', 'Late')->count();
        $absent = $attendances->where('status', 'Absent')->count();
        $notCheckedOut = $attendances->where('status', 'Not-Checked-Out')->count();

        return [
            'total_records' => $total,
            'present_days' => $present,
            'absent_days' => $absent,
            'on_time' => $onTime,
            'late' => $late,
            'not_checked_out' => $notCheckedOut,
            'attendance_rate' => $total > 0 ? round(($present / $total) * 100, 2) : 0,
            'punctuality_rate' => $present > 0 ? round(($onTime / $present) * 100, 2) : 0,
        ];
    }

    /**
     * Update attendance notes (admin only)
     */
    public function updateNotes(Request $request, Attendance $attendance)
    {
        $request->validate([
            'notes' => 'nullable|string|max:1000',
        ]);

        $attendance->update([
            'notes' => $request->notes,
        ]);

        return redirect()->back()->with('success', 'Catatan absensi berhasil diperbarui.');
    }

    /**
     * Display maps dashboard for real-time attendance tracking
     */
    public function maps(): Response
    {
        $today = Carbon::now()->format('Y-m-d');
        
        // Get today's attendances with location data
        $attendances = Attendance::with(['user', 'user.division'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'peserta');
            })
            ->where('date', $today)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'user_name' => $attendance->user->name,
                    'division' => $attendance->user->division->name ?? 'N/A',
                    'check_in_time' => $attendance->check_in,
                    'check_out_time' => $attendance->check_out,
                    'latitude' => $attendance->latitude,
                    'longitude' => $attendance->longitude,
                    'status' => $attendance->status,
                    'is_valid_location' => $this->isValidLocation(
                        $attendance->latitude,
                        $attendance->longitude
                    ),
                ];
            });

        // Statistics for today
        $stats = [
            'total_attendances' => $attendances->count(),
            'valid_attendances' => $attendances->where('is_valid_location', true)->count(),
            'invalid_attendances' => $attendances->where('is_valid_location', false)->count(),
            'on_time' => $attendances->where('status', 'On-Time')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
        ];

        // Office location (Bank Indonesia KPw Lampung)
        $officeLocation = [
            'latitude' => -5.3971,
            'longitude' => 105.2669,
            'radius' => 200, // 200 meters
            'name' => 'Bank Indonesia KPw Lampung'
        ];

        return Inertia::render('Admin/Maps/Index', [
            'attendances' => $attendances,
            'stats' => $stats,
            'officeLocation' => $officeLocation,
            'currentDate' => $today,
        ]);
    }

    /**
     * API endpoint to get real-time attendance data for maps
     */
    public function getAttendanceLocations(Request $request)
    {
        $date = $request->get('date', Carbon::now()->format('Y-m-d'));
        
        $attendances = Attendance::with(['user', 'user.division'])
            ->whereHas('user', function ($q) {
                $q->where('role', 'peserta');
            })
            ->where('date', $date)
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get()
            ->map(function ($attendance) {
                return [
                    'id' => $attendance->id,
                    'user_id' => $attendance->user_id,
                    'user_name' => $attendance->user->name,
                    'division' => $attendance->user->division->name ?? 'N/A',
                    'check_in_time' => $attendance->check_in,
                    'check_out_time' => $attendance->check_out,
                    'latitude' => (float) $attendance->latitude,
                    'longitude' => (float) $attendance->longitude,
                    'status' => $attendance->status,
                    'is_valid_location' => $this->isValidLocation(
                        $attendance->latitude,
                        $attendance->longitude
                    ),
                    'updated_at' => $attendance->updated_at->toISOString(),
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $attendances,
            'stats' => [
                'total_attendances' => $attendances->count(),
                'valid_attendances' => $attendances->where('is_valid_location', true)->count(),
                'invalid_attendances' => $attendances->where('is_valid_location', false)->count(),
            ]
        ]);
    }

    /**
     * Check if location is within valid radius from office
     */
    private function isValidLocation($latitude, $longitude): bool
    {
        // Office coordinates (Bank Indonesia KPw Lampung)
        $officeLat = -5.3971;
        $officeLng = 105.2669;
        $radius = 200; // meters

        $distance = $this->calculateDistance($officeLat, $officeLng, $latitude, $longitude);
        
        return $distance <= $radius;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earth_radius = 6371000; // Earth radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earth_radius * $c;
    }
}

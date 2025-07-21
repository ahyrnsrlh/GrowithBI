<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function index()
    {
        // General Statistics
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'menunggu')->count(),
            'accepted_applications' => Application::where('status', 'diterima')->count(),
            'rejected_applications' => Application::where('status', 'ditolak')->count(),
            'total_divisions' => Division::count(),
            'active_divisions' => Division::where('is_active', true)->count(),
            'total_supervisors' => User::where('role', 'pembimbing')->count(),
            'total_participants' => User::where('role', 'peserta')->count(),
        ];

        // Application trends by month (last 6 months)
        $applicationTrends = Application::select(
            DB::raw('YEAR(created_at) as year'),
            DB::raw('MONTH(created_at) as month'),
            DB::raw('COUNT(*) as total'),
            DB::raw('SUM(CASE WHEN status = "diterima" THEN 1 ELSE 0 END) as accepted'),
            DB::raw('SUM(CASE WHEN status = "ditolak" THEN 1 ELSE 0 END) as rejected'),
            DB::raw('SUM(CASE WHEN status = "menunggu" THEN 1 ELSE 0 END) as pending')
        )
        ->where('created_at', '>=', now()->subMonths(6))
        ->groupBy('year', 'month')
        ->orderBy('year')
        ->orderBy('month')
        ->get()
        ->map(function ($item) {
            return [
                'month' => $item->year . '-' . str_pad($item->month, 2, '0', STR_PAD_LEFT),
                'total' => $item->total,
                'accepted' => $item->accepted,
                'rejected' => $item->rejected,
                'pending' => $item->pending
            ];
        });

        // Division performance
        $divisionPerformance = Division::with('supervisor')
            ->withCount([
                'applications',
                'applications as accepted_count' => function ($query) {
                    $query->where('status', 'diterima');
                },
                'applications as rejected_count' => function ($query) {
                    $query->where('status', 'ditolak');
                }
            ])
            ->get()
            ->map(function ($division) {
                $total = $division->applications_count;
                $acceptance_rate = $total > 0 ? round(($division->accepted_count / $total) * 100, 1) : 0;
                $quota_filled = $division->quota > 0 ? round(($division->accepted_count / $division->quota) * 100, 1) : 0;
                
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'supervisor' => $division->supervisor ? $division->supervisor->name : 'Belum ditentukan',
                    'quota' => $division->quota,
                    'applications_count' => $division->applications_count,
                    'accepted_count' => $division->accepted_count,
                    'rejected_count' => $division->rejected_count,
                    'acceptance_rate' => $acceptance_rate,
                    'quota_filled' => $quota_filled,
                ];
            });

        // Top performers (supervisors)
        $topSupervisors = User::where('role', 'pembimbing')
            ->withCount([
                'supervisedDivisions',
                'supervisedApplications',
                'supervisedApplications as accepted_applications_count' => function ($query) {
                    $query->where('status', 'diterima');
                }
            ])
            ->having('supervised_applications_count', '>', 0)
            ->orderBy('accepted_applications_count', 'desc')
            ->take(5)
            ->get()
            ->map(function ($supervisor) {
                $total = $supervisor->supervised_applications_count;
                $acceptance_rate = $total > 0 ? round(($supervisor->accepted_applications_count / $total) * 100, 1) : 0;
                
                return [
                    'id' => $supervisor->id,
                    'name' => $supervisor->name,
                    'divisions_count' => $supervisor->supervised_divisions_count,
                    'applications_count' => $supervisor->supervised_applications_count,
                    'accepted_count' => $supervisor->accepted_applications_count,
                    'acceptance_rate' => $acceptance_rate,
                ];
            });

        // Status distribution
        $statusDistribution = [
            'pending' => $stats['pending_applications'],
            'accepted' => $stats['accepted_applications'],
            'rejected' => $stats['rejected_applications']
        ];

        return Inertia::render('Admin/Reports/Index', [
            'stats' => $stats,
            'applicationTrends' => $applicationTrends,
            'divisionPerformance' => $divisionPerformance,
            'topSupervisors' => $topSupervisors,
            'statusDistribution' => $statusDistribution
        ]);
    }

    public function export(Request $request)
    {
        $type = $request->get('type', 'applications');
        
        // TODO: Implement export functionality based on type
        // Types: applications, divisions, participants, supervisors
        
        return response()->json([
            'message' => 'Export functionality will be implemented soon',
            'type' => $type
        ]);
    }
}

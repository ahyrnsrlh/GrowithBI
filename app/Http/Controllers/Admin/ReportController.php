<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use App\Models\User;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Response;

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
        $divisionPerformance = Division::withCount([
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
                    'quota' => $division->quota,
                    'applications_count' => $division->applications_count,
                    'accepted_count' => $division->accepted_count,
                    'rejected_count' => $division->rejected_count,
                    'acceptance_rate' => $acceptance_rate,
                    'quota_filled' => $quota_filled,
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

    /**
     * Export applications data to Excel
     */
    public function exportApplications(Request $request)
    {
        $applications = \App\Models\Application::with(['user', 'division'])
            ->orderBy('created_at', 'desc')
            ->get();

        $csvData = [];
        $csvData[] = ['No', 'Nama', 'Email', 'Divisi', 'Status', 'Tanggal Daftar'];

        foreach ($applications as $index => $application) {
            $csvData[] = [
                $index + 1,
                $application->user->name,
                $application->user->email,
                $application->division ? $application->division->name : '-',
                ucfirst($application->status),
                $application->created_at->format('Y-m-d H:i:s')
            ];
        }

        $filename = 'laporan_aplikasi_' . date('Y-m-d_H-i-s') . '.csv';
        
        $callback = function() use ($csvData) {
            $file = fopen('php://output', 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    /**
     * Export logbooks to Excel format
     */
    public function exportLogbooks(Request $request)
    {
        $user = auth()->user();
        
        $query = Logbook::with(['user.division', 'division', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // If user is mentor, only show logbooks from their division
        if ($user->role === 'mentor') {
            $query->where('division_id', $user->division_id);
        }

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('division') && $user->role === 'admin') {
            $query->where('division_id', $request->division);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('date', '<=', $request->date_to);
        }

        $logbooks = $query->get();

        // Create CSV content
        $csvContent = "No,Nama Peserta,Email,Divisi,Tanggal,Judul,Aktivitas,Durasi,Status,Dibuat Pada,Diperbarui Pada\n";
        
        foreach ($logbooks as $index => $logbook) {
            $csvContent .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $index + 1,
                $logbook->user->name ?? '',
                $logbook->user->email ?? '',
                $logbook->user->division->name ?? '',
                $logbook->date ?? '',
                str_replace('"', '""', $logbook->title ?? ''),
                str_replace('"', '""', $logbook->activities ?? ''),
                $logbook->duration ? $logbook->duration . ' jam' : '',
                $this->getStatusLabel($logbook->status),
                $logbook->created_at ? $logbook->created_at->format('Y-m-d H:i:s') : '',
                $logbook->updated_at ? $logbook->updated_at->format('Y-m-d H:i:s') : ''
            );
        }

        $filename = 'laporan_logbook_' . date('Y-m-d_H-i-s') . '.csv';

        return Response::make($csvContent, 200, [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            'Cache-Control' => 'must-revalidate, post-check=0, pre-check=0',
            'Pragma' => 'public'
        ]);
    }

    /**
     * Get status label in Indonesian
     */
    private function getStatusLabel($status)
    {
        switch ($status) {
            case 'draft':
                return 'Draft';
            case 'submitted':
                return 'Diajukan';
            case 'approved':
                return 'Disetujui';
            case 'rejected':
                return 'Ditolak';
            case 'revision':
                return 'Perlu Revisi';
            default:
                return 'Tidak Diketahui';
        }
    }
}

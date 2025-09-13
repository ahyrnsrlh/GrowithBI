<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Report;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class FinalReportController extends Controller
{
    /**
     * Display a listing of final reports
     */
    public function index(Request $request)
    {
        $query = Report::with(['user', 'application.division', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('division')) {
            $query->whereHas('application.division', function ($q) use ($request) {
                $q->where('id', $request->division);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhereHas('user', function ($q) use ($search) {
                      $q->where('name', 'like', "%{$search}%");
                  });
            });
        }

        $reports = $query->paginate(20);

        // Get divisions for filter
        $divisions = Division::where('is_active', true)->get();

        // Statistics
        $stats = [
            'total' => Report::count(),
            'submitted' => Report::where('status', 'submitted')->count(),
            'approved' => Report::where('status', 'approved')->count(),
            'revision' => Report::where('status', 'revision')->count(),
        ];

        return Inertia::render('Admin/FinalReports/Index', [
            'reports' => $reports,
            'divisions' => $divisions,
            'stats' => $stats,
            'filters' => $request->only(['status', 'division', 'search'])
        ]);
    }

    /**
     * Display the specified report
     */
    public function show(Report $report)
    {
        $report->load(['user', 'application.division', 'reviewer']);
        
        return Inertia::render('Admin/FinalReports/Show', [
            'report' => $report
        ]);
    }

    /**
     * Update report status
     */
    public function updateStatus(Request $request, Report $report)
    {
        $request->validate([
            'status' => 'required|in:submitted,approved,revision',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $report->update([
            'status' => $request->status,
            'feedback' => $request->feedback,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return back()->with('success', 'Status laporan berhasil diperbarui.');
    }

    /**
     * Download report file
     */
    public function download(Report $report)
    {
        if (!Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($report->file_path), $report->file_name);
    }

    /**
     * Add feedback to report
     */
    public function addFeedback(Request $request, Report $report)
    {
        $request->validate([
            'feedback' => 'required|string|max:1000'
        ]);

        $report->update([
            'feedback' => $request->feedback,
            'reviewed_by' => Auth::id(),
            'reviewed_at' => now()
        ]);

        return back()->with('success', 'Feedback berhasil ditambahkan.');
    }

    /**
     * Export all final reports to CSV
     */
    public function exportAll(Request $request)
    {
        $query = Report::with(['user', 'application.division', 'reviewer'])
            ->orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('division')) {
            $query->whereHas('application.division', function ($q) use ($request) {
                $q->where('id', $request->division);
            });
        }

        $reports = $query->get();

        // Create CSV content
        $csvContent = "No,Nama Peserta,Email,Divisi,Judul Laporan,Status,Tanggal Submit,Reviewer,Tanggal Review,Feedback\n";
        
        foreach ($reports as $index => $report) {
            $csvContent .= sprintf(
                "%d,\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\",\"%s\"\n",
                $index + 1,
                $report->user->name ?? '',
                $report->user->email ?? '',
                $report->application->division->name ?? '',
                str_replace('"', '""', $report->title ?? ''),
                $this->getStatusLabel($report->status),
                $report->created_at ? $report->created_at->format('Y-m-d H:i:s') : '',
                $report->reviewer->name ?? '',
                $report->reviewed_at ? $report->reviewed_at->format('Y-m-d H:i:s') : '',
                str_replace('"', '""', $report->feedback ?? '')
            );
        }

        $filename = 'laporan_akhir_' . date('Y-m-d_H-i-s') . '.csv';

        return response($csvContent, 200, [
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
            case 'submitted':
                return 'Menunggu Review';
            case 'approved':
                return 'Disetujui';
            case 'revision':
                return 'Perlu Revisi';
            default:
                return 'Tidak Diketahui';
        }
    }
}
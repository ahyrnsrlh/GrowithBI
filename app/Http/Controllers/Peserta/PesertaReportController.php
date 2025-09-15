<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Report;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Carbon\Carbon;

class PesertaReportController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has accepted application
        $acceptedApplication = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->with('division')
            ->first();
            
        if (!$acceptedApplication) {
            return redirect()->route('peserta.dashboard')
                ->with('error', 'Anda belum memiliki status magang yang diterima untuk mengakses laporan.');
        }

        // Get user's reports
        $reports = Report::where('user_id', $user->id)
            ->where('application_id', $acceptedApplication->id)
            ->with(['reviewer'])
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user's logbooks for statistics
        $logbooks = Logbook::where('user_id', $user->id)
            ->with(['division'])
            ->orderBy('date', 'desc')
            ->get();

        // Calculate statistics
        $stats = [
            'total_logbooks' => $logbooks->count(),
            'approved_logbooks' => $logbooks->where('status', 'approved')->count(),
            'pending_logbooks' => $logbooks->where('status', 'submitted')->count(),
            'revision_logbooks' => $logbooks->where('status', 'revision')->count(),
            'total_hours' => $logbooks->sum('duration'),
            'division' => $acceptedApplication->division->name,
            'start_date' => $acceptedApplication->created_at,
            'internship_duration' => $acceptedApplication->created_at->diffInDays(now()),
            'total_reports' => $reports->count(),
            'approved_reports' => $reports->where('status', 'approved')->count(),
            'pending_reports' => $reports->where('status', 'submitted')->count(),
            'revision_reports' => $reports->where('status', 'revision')->count(),
        ];

        return Inertia::render('Profile/Reports/Index', [
            'stats' => $stats,
            'reports' => $reports,
            'logbooks' => $logbooks->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'title' => $logbook->title,
                    'date' => $logbook->date,
                    'activities' => $logbook->activities,
                    'duration' => $logbook->duration,
                    'status' => $logbook->status,
                    'created_at' => $logbook->created_at
                ];
            }),
            'application' => $acceptedApplication
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $user = Auth::user();
        
        $acceptedApplication = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->with('division')
            ->first();
            
        if (!$acceptedApplication) {
            return redirect()->route('peserta.reports.index')
                ->with('error', 'Anda belum memiliki status magang yang diterima.');
        }

        // Get user's logbooks for the report
        $logbooks = Logbook::where('user_id', $user->id)
            ->where('status', 'approved')
            ->orderBy('date', 'asc')
            ->get();

        return Inertia::render('Profile/Reports/Create', [
            'division' => $acceptedApplication->division,
            'logbooks' => $logbooks,
            'application' => $acceptedApplication
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        
        // Check if user has accepted application
        $acceptedApplication = Application::where('user_id', $user->id)
            ->where('status', 'diterima')
            ->first();
            
        if (!$acceptedApplication) {
            return back()->with('error', 'Anda belum memiliki status magang yang diterima.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'report_file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
        ]);

        try {
            // Store the file
            $file = $request->file('report_file');
            $filename = 'report_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('reports', $filename, 'public');

            // Create report record
            Report::create([
                'user_id' => $user->id,
                'application_id' => $acceptedApplication->id,
                'title' => $request->title,
                'description' => $request->description,
                'file_path' => $filePath,
                'file_name' => $file->getClientOriginalName(),
                'file_size' => $file->getSize(),
                'file_type' => $file->getMimeType(),
                'status' => 'submitted'
            ]);

            return redirect()->route('profile.edit')
                ->with('success', 'Laporan berhasil diupload dan dikirim untuk review.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat mengupload laporan.')
                        ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $user = Auth::user();
        $report = Report::where('user_id', $user->id)->findOrFail($id);
        
        return Inertia::render('Profile/Reports/Show', [
            'report' => $report->load(['reviewer', 'application.division'])
        ]);
    }

    /**
     * Download report file
     */
    public function download(Report $report)
    {
        $user = Auth::user();
        
        // Check if user owns this report
        if ($report->user_id !== $user->id) {
            abort(403, 'Unauthorized access to report.');
        }

        if (!Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        return Storage::disk('public')->download($report->file_path, $report->file_name);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $user = Auth::user();
        $report = Report::where('user_id', $user->id)->findOrFail($id);
        
        // Only allow editing if status is draft or revision
        if (!in_array($report->status, ['draft', 'revision'])) {
            return redirect()->route('profile.edit')
                ->with('error', 'Laporan tidak dapat diedit karena sudah disubmit atau disetujui.');
        }
        
        return Inertia::render('Profile/Reports/Edit', [
            'report' => $report
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $user = Auth::user();
        $report = Report::where('user_id', $user->id)->findOrFail($id);
        
        // Only allow updating if status is draft or revision
        if (!in_array($report->status, ['draft', 'revision'])) {
            return back()->with('error', 'Laporan tidak dapat diedit karena sudah disubmit atau disetujui.');
        }

        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'report_file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx|max:10240', // 10MB max
        ]);

        try {
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'submitted'
            ];

            // If new file is uploaded, replace the old one
            if ($request->hasFile('report_file')) {
                // Delete old file
                if (Storage::disk('public')->exists($report->file_path)) {
                    Storage::disk('public')->delete($report->file_path);
                }

                // Store new file
                $file = $request->file('report_file');
                $filename = 'report_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('reports', $filename, 'public');

                $updateData = array_merge($updateData, [
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                ]);
            }

            $report->update($updateData);

            return redirect()->route('profile.edit')
                ->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat memperbarui laporan.')
                        ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $user = Auth::user();
        $report = Report::where('user_id', $user->id)->findOrFail($id);
        
        // Only allow deleting if status is draft or revision
        if (!in_array($report->status, ['draft', 'revision'])) {
            return back()->with('error', 'Laporan tidak dapat dihapus karena sudah disubmit atau disetujui.');
        }

        try {
            // Delete file from storage
            if (Storage::disk('public')->exists($report->file_path)) {
                Storage::disk('public')->delete($report->file_path);
            }

            // Delete report record
            $report->delete();

            return redirect()->route('profile.edit')
                ->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat menghapus laporan.');
        }
    }
}

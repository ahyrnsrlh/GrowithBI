<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Report;
use App\Models\Logbook;
use App\Models\User;
use App\Http\Requests\StoreReportRequest;
use App\Http\Requests\UpdateReportRequest;
use App\Notifications\ReportNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }
        
        // Statuses that indicate accepted application
        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];
        
        // Check if user has accepted application
        $acceptedApplication = Application::where('user_id', $user->id)
            ->whereIn('status', $acceptedStatuses)
            ->with('division')
            ->first();
            
        if (!$acceptedApplication) {
            return redirect()->route('profile.edit')
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

        return Inertia::render('Peserta/Reports/Index', [
            'stats' => $stats,
            'reports' => $reports,
            'logbooks' => $logbooks->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'title' => $logbook->title ?? 'Aktivitas Harian',
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
        
        // Statuses that indicate accepted application
        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];
        
        $acceptedApplication = Application::where('user_id', $user->id)
            ->whereIn('status', $acceptedStatuses)
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

        return Inertia::render('Peserta/Reports/Create', [
            'division' => $acceptedApplication->division,
            'logbooks' => $logbooks->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'title' => $logbook->title ?? 'Aktivitas Harian',
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
     * Store a newly created resource in storage.
     */
    public function store(StoreReportRequest $request)
    {
        /** @var User|null $user */
        $user = $request->user();

        if (!$user) {
            abort(401);
        }
        
        // Statuses that indicate accepted application
        $acceptedStatuses = ['accepted', 'letter_ready', 'diterima'];
        
        // Check if user has accepted application
        $acceptedApplication = Application::where('user_id', $user->id)
            ->whereIn('status', $acceptedStatuses)
            ->first();
            
        if (!$acceptedApplication) {
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Anda belum memiliki status magang yang diterima.'], 400);
            }
            return back()->with('error', 'Anda belum memiliki status magang yang diterima.');
        }

        $filePath = null;

        \DB::beginTransaction();
        try {
            // Store the file
            $file = $request->file('report_file');
            $filename = 'report_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('reports', $filename, 'public');

            // Create report record
            $report = Report::create([
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

            \DB::commit();

            // Send notification to user
            $user->notify(new ReportNotification($report, 'submitted'));
            
            // Send notification to all admins about new report
            $admins = User::where('role', 'admin')->get();
            foreach ($admins as $admin) {
                /** @var User $admin */
                $admin->notify(new ReportNotification($report, 'submitted'));
            }

            if ($request->wantsJson()) {
                return response()->json(['success' => true, 'report' => $report]);
            }

            return back()->with('success', 'Laporan berhasil diunggah.');
        } catch (\Exception $e) {
            \DB::rollBack();
            // Delete newly uploaded file if it was created
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
            Log::error('Report upload error: ' . $e->getMessage());
            if ($request->wantsJson()) {
                return response()->json(['error' => 'Gagal mengupload laporan. Silakan coba lagi.'], 500);
            }
            return back()->with('error', 'Gagal mengupload laporan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, string $id)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }
        $report = Report::where('user_id', $user->id)->findOrFail($id);
        $report->load(['reviewer', 'application.division']);

        if ($request->wantsJson()) {
            return response()->json([
                'report' => $report
            ]);
        }
        
        return Inertia::render('Peserta/Reports/Show', [
            'report' => $report
        ]);
    }

    /**
     * Download report file
     */
    public function download(Report $report)
    {
        /** @var User|null $user */
        $user = Auth::user();

        if (!$user) {
            abort(401);
        }
        
        // Check if user owns this report
        if ($report->user_id !== $user->id) {
            abort(403, 'Unauthorized access to report.');
        }

        if (!Storage::disk('public')->exists($report->file_path)) {
            return back()->with('error', 'File laporan tidak ditemukan.');
        }

        return response()->download(Storage::disk('public')->path($report->file_path), $report->file_name);
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
            return redirect()->route('peserta.reports.show', $report->id)
                ->with('error', 'Laporan tidak dapat diedit karena sudah disubmit atau disetujui.');
        }
        
        return Inertia::render('Peserta/Reports/Edit', [
            'report' => $report
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateReportRequest $request, string $id)
    {
        $user = Auth::user();
        $report = Report::where('user_id', $user->id)->findOrFail($id);
        
        // Only allow updating if status is draft, submitted, or revision
        if (!in_array($report->status, ['draft', 'submitted', 'revision'])) {
            return back()->with('error', 'Laporan tidak dapat diedit karena sudah disubmit atau disetujui.');
        }

        $newFileStored = null;
        $oldFileToDelete = null;

        \DB::beginTransaction();
        try {
            $updateData = [
                'title' => $request->title,
                'description' => $request->description,
                'status' => 'submitted'
            ];

            // If new file is uploaded, replace the old one
            if ($request->hasFile('report_file')) {
                // Queue old file to delete after commit
                $oldFileToDelete = $report->file_path;

                // Store new file
                $file = $request->file('report_file');
                $filename = 'report_' . $user->id . '_' . time() . '.' . $file->getClientOriginalExtension();
                $filePath = $file->storeAs('reports', $filename, 'public');
                $newFileStored = $filePath;

                $updateData = array_merge($updateData, [
                    'file_path' => $filePath,
                    'file_name' => $file->getClientOriginalName(),
                    'file_size' => $file->getSize(),
                    'file_type' => $file->getMimeType(),
                ]);
            }

            $report->update($updateData);

            \DB::commit();

            // Delete old file from storage only after transaction commits successfully
            if ($oldFileToDelete && Storage::disk('public')->exists($oldFileToDelete)) {
                Storage::disk('public')->delete($oldFileToDelete);
            }

            return back()->with('success', 'Laporan berhasil diperbarui.');
        } catch (\Exception $e) {
            \DB::rollBack();
            // Clean up new file from storage if transaction fails
            if ($newFileStored && Storage::disk('public')->exists($newFileStored)) {
                Storage::disk('public')->delete($newFileStored);
            }
            Log::error('Report update error: ' . $e->getMessage());
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
        
        // Only allow deleting if status is draft, submitted, or revision
        if (!in_array($report->status, ['draft', 'submitted', 'revision'])) {
            return back()->with('error', 'Laporan tidak dapat dihapus karena sudah disubmit atau disetujui.');
        }

        $fileToDelete = $report->file_path;

        \DB::beginTransaction();
        try {
            // Delete report record first
            $report->delete();

            \DB::commit();

            // Delete file from storage only after successful commit
            if ($fileToDelete && Storage::disk('public')->exists($fileToDelete)) {
                Storage::disk('public')->delete($fileToDelete);
            }

            return back()->with('success', 'Laporan berhasil dihapus.');
        } catch (\Exception $e) {
            \DB::rollBack();
            Log::error('Report deletion error: ' . $e->getMessage());
            return back()->with('error', 'Terjadi kesalahan saat menghapus laporan.');
        }
    }
}

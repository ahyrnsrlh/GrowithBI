<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RegistrationStatus;
use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Services\ApplicationDeletionService;
use App\Models\ApplicationEvaluation;
use App\Services\SelectionScoreService;

use Inertia\Inertia;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Application::with(['division', 'user']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by division
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        // Search by name or email - now search in user table via relationship
        if ($request->filled('search')) {
            $query->whereHas('user', function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $applications = $query->latest()->paginate(10)->withQueryString();

        // Add status info and permissions to each application for frontend
        $applications->getCollection()->transform(function ($application) {
            $application->status_info = $application->status_info;
            $application->available_transitions = $application->available_transitions;
            $application->can_delete = Auth::user()?->role === 'admin';
            return $application;
        });

        $divisions = Division::select('id', 'name')->get();

        // Use enum for stats
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', RegistrationStatus::MENUNGGU->value)->count(),
            'in_review' => Application::where('status', RegistrationStatus::IN_REVIEW->value)->count(),
            'accepted' => Application::whereIn('status', [
                RegistrationStatus::ACCEPTED->value,
                RegistrationStatus::LETTER_READY->value,
            ])->count(),
            'rejected' => Application::where('status', RegistrationStatus::REJECTED->value)->count(),
        ];

        // Get status options for filter dropdown
        $statusOptions = RegistrationStatus::options();

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $applications,
            'divisions' => $divisions,
            'filters' => $request->only(['status', 'division', 'search']),
            'stats' => $stats,
            'statusOptions' => $statusOptions,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Application $application, SelectionScoreService $scoreService)
    {
        $application->load(['division', 'user', 'letterUploader', 'reviewer', 'evaluation.reviewer']);

        // Add computed attributes for frontend
        $application->status_info = $application->status_info;
        $application->available_transitions = $application->available_transitions;
        $application->can_download_letter = $application->canDownloadAcceptanceLetter();
        $application->can_delete = Auth::user()?->role === 'admin';

        // Prepare evaluation data and weights
        $evaluationData = $application->evaluation 
            ? $scoreService->prepareScoreSummaryForFrontend($application->evaluation) 
            : null;

        $weights = config('selection.weights', [
            'competency' => 40,
            'motivation' => 30,
            'interview' => 30
        ]);

        return Inertia::render('Admin/Applications/Show', [
            'application' => $application,
            'statusOptions' => RegistrationStatus::options(),
            'evaluation' => $evaluationData,
            'selectionWeights' => $weights,
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     * 
     * Uses the Application model's transitionTo method which:
     * - Validates the status transition
     * - Wraps update in a transaction
     * - Dispatches appropriate events for notifications
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => ['required', 'string', 'in:' . implode(',', RegistrationStatus::values())],
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:1000',
            'interview_date' => 'nullable|date',
            'interview_time' => 'nullable|date_format:H:i',
            'interview_location' => 'nullable|string|max:255',
            'interview_location_detail' => 'nullable|string|max:500',
        ]);

        try {
            $newStatus = RegistrationStatus::from($request->status);
            $admin = Auth::user();

            // Build metadata from request
            $metadata = array_filter([
                'admin_notes' => $request->admin_notes,
                'rejection_reason' => $request->rejection_reason,
                'interview_date' => $request->interview_date,
                'interview_time' => $request->interview_time,
                'interview_location' => $request->interview_location,
                'interview_location_detail' => $request->interview_location_detail,
            ]);

            // Use the model's transitionTo method (handles events & notifications)
            $success = $application->transitionTo($newStatus, $admin, $metadata);

            if (!$success) {
                return redirect()->back()->with('error', 'Transisi status tidak valid dari ' . $application->status . ' ke ' . $request->status);
            }

            Log::info('Application status updated', [
                'application_id' => $application->id,
                'new_status' => $request->status,
                'admin_id' => $admin?->id,
            ]);

            return redirect()->back()->with('success', 'Status pendaftaran berhasil diupdate.');

        } catch (\ValueError $e) {
            return redirect()->back()->with('error', 'Status tidak valid: ' . $request->status);
        } catch (\Exception $e) {
            Log::error('Failed to update application status', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    /**
     * Bulk update applications status
     */
    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'status' => ['required', 'string', 'in:' . implode(',', RegistrationStatus::values())],
            'admin_notes' => 'nullable|string|max:1000',
            'rejection_reason' => 'nullable|string|max:1000',
        ]);

        try {
            $newStatus = RegistrationStatus::from($request->status);
            $admin = Auth::user();
            $metadata = array_filter([
                'admin_notes' => $request->admin_notes,
                'rejection_reason' => $request->rejection_reason,
            ]);

            $successCount = 0;
            $failedCount = 0;

            // Process each application individually to trigger proper events
            $applications = Application::whereIn('id', $request->application_ids)->get();
            
            foreach ($applications as $application) {
                $success = $application->transitionTo($newStatus, $admin, $metadata);
                if ($success) {
                    $successCount++;
                } else {
                    $failedCount++;
                }
            }

            $message = "Berhasil mengupdate {$successCount} pendaftaran.";
            if ($failedCount > 0) {
                $message .= " {$failedCount} pendaftaran gagal diupdate (transisi tidak valid).";
            }

            return redirect()->back()->with('success', $message);

        } catch (\ValueError $e) {
            return redirect()->back()->with('error', 'Status tidak valid: ' . $request->status);
        } catch (\Exception $e) {
            Log::error('Failed to bulk update applications', [
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Gagal mengupdate status: ' . $e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Application $application, ApplicationDeletionService $deletionService)
    {
        $admin = Auth::user();

        // Only allow users with the 'admin' role to perform deletion
        if (!$admin || $admin->role !== 'admin') {
            abort(403, 'Unauthorized: only admin may delete applications.');
        }

        try {
            $application->update([
                'status' => 'cancelled',
                'cancelled_at' => now(),
                'cancelled_by' => 'Admin',
                'cancellation_reason' => 'Dibatalkan oleh Admin',
            ]);

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Pendaftaran berhasil dibatalkan.'], 200);
            }

            return redirect()->route('admin.applications.index')->with('success', 'Pendaftaran berhasil dibatalkan.');

        } catch (\Exception $e) {
            Log::error('Failed to delete application', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);

            if (request()->wantsJson()) {
                return response()->json(['message' => 'Gagal menghapus pendaftar. Silakan coba lagi.'], 500);
            }

            return redirect()->back()->with('error', 'Gagal menghapus pendaftar: ' . $e->getMessage());
        }
    }

    /**
     * Export applications data to CSV
     */
    public function exportApplications(Request $request)
    {
        $query = Application::with(['user', 'division'])
            ->orderBy('created_at', 'desc');

        // Apply filters if provided
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        $applications = $query->get();

        $csvData = [];
        $csvData[] = ['No', 'Nama', 'Email', 'Divisi', 'Status', 'Tanggal Daftar', 'Catatan Admin'];

        foreach ($applications as $index => $application) {
            $csvData[] = [
                $index + 1,
                $application->user->name,
                $application->user->email,
                $application->division ? $application->division->name : '-',
                ucfirst($application->status),
                $application->created_at->format('Y-m-d H:i:s'),
                $application->admin_notes ?? ''
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
     * Create or update evaluation scores for an application.
     */
    public function evaluate(Request $request, Application $application, SelectionScoreService $scoreService)
    {
        $request->validate([
            'competency_score' => 'required|numeric|min:0|max:100',
            'motivation_score' => 'required|numeric|min:0|max:100',
            'interview_score' => 'required|numeric|min:0|max:100',
            'reviewer_notes' => 'nullable|string|max:2000',
        ]);

        $admin = Auth::user();
        if (!$admin || !in_array($admin->role, ['admin', 'pembimbing'])) {
            abort(403, 'Unauthorized.');
        }

        $weights = config('selection.weights', [
            'competency' => 40,
            'motivation' => 30,
            'interview' => 30
        ]);

        try {
            $compScore = (float) $request->competency_score;
            $motScore = (float) $request->motivation_score;
            $intScore = (float) $request->interview_score;

            $finalScore = $scoreService->calculateFinalScore($compScore, $motScore, $intScore, $weights);
            $recLevel = $scoreService->generateRecommendationLevel($finalScore);

            $evaluation = $application->evaluation;

            if ($evaluation) {
                // Keep history (audit trail)
                $history = $evaluation->score_history ?? [];
                $history[] = [
                    'reviewer_id' => $evaluation->reviewer_id,
                    'reviewer_name' => $evaluation->reviewer?->name ?? 'Sebelumnya',
                    'reviewed_at' => $evaluation->updated_at->toIso8601String(),
                    'competency_score' => $evaluation->competency_score,
                    'motivation_score' => $evaluation->motivation_score,
                    'interview_score' => $evaluation->interview_score,
                    'final_score' => $evaluation->final_score,
                    'reviewer_notes' => $evaluation->reviewer_notes,
                ];

                $evaluation->update([
                    'competency_score' => $compScore,
                    'motivation_score' => $motScore,
                    'interview_score' => $intScore,
                    'competency_weight' => $weights['competency'],
                    'motivation_weight' => $weights['motivation'],
                    'interview_weight' => $weights['interview'],
                    'final_score' => $finalScore,
                    'recommendation_level' => $recLevel,
                    'reviewer_notes' => $request->reviewer_notes,
                    'reviewer_id' => $admin->id,
                    'score_history' => $history,
                ]);
            } else {
                $application->evaluation()->create([
                    'competency_score' => $compScore,
                    'motivation_score' => $motScore,
                    'interview_score' => $intScore,
                    'competency_weight' => $weights['competency'],
                    'motivation_weight' => $weights['motivation'],
                    'interview_weight' => $weights['interview'],
                    'final_score' => $finalScore,
                    'recommendation_level' => $recLevel,
                    'reviewer_notes' => $request->reviewer_notes,
                    'reviewer_id' => $admin->id,
                    'score_history' => [],
                ]);
            }

            return redirect()->back()->with('success', 'Evaluasi pendaftar berhasil disimpan.');

        } catch (\Exception $e) {
            Log::error('Failed to save evaluation', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
            return redirect()->back()->with('error', 'Gagal menyimpan evaluasi: ' . $e->getMessage());
        }
    }
}

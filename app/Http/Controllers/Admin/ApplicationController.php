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
use Illuminate\Validation\Rules\Enum;
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

        // Add status info to each application for frontend
        $applications->getCollection()->transform(function ($application) {
            $application->status_info = $application->status_info;
            $application->available_transitions = $application->available_transitions;
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
    public function show($id)
    {
        $application = Application::with(['division', 'user', 'letterUploader', 'reviewer'])->findOrFail($id);
        
        // Add computed attributes for frontend
        $application->status_info = $application->status_info;
        $application->available_transitions = $application->available_transitions;
        $application->can_download_letter = $application->canDownloadAcceptanceLetter();
        
        return Inertia::render('Admin/Applications/Show', [
            'application' => $application,
            'statusOptions' => RegistrationStatus::options(),
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
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
    public function destroy(string $id)
    {
        //
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
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use App\Notifications\ApplicationVerified;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
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

        $divisions = Division::select('id', 'name')->get();

        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'menunggu')->count(),
            'accepted' => Application::where('status', 'diterima')->count(),
            'rejected' => Application::where('status', 'ditolak')->count(),
        ];

        return Inertia::render('Admin/Applications/Index', [
            'applications' => $applications,
            'divisions' => $divisions,
            'filters' => $request->only(['status', 'division', 'search']),
            'stats' => $stats
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
        $application = Application::with(['division', 'user', 'letterUploader'])->findOrFail($id);
        
        return Inertia::render('Admin/Applications/Show', [
            'application' => $application
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
     */
    public function update(Request $request, Application $application)
    {
        $request->validate([
            'status' => 'required|in:menunggu,diterima,ditolak',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        $application->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id() ?? 1 // For now, default to admin
        ]);

        // Send notification to user if status is diterima or ditolak
        if ($request->status === 'diterima') {
            $application->user->notify(new RegistrationStatusNotification($application, 'accepted'));
        } elseif ($request->status === 'ditolak') {
            $application->user->notify(new RegistrationStatusNotification($application, 'rejected'));
        }

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diupdate.');
    }

    public function bulkUpdate(Request $request)
    {
        $request->validate([
            'application_ids' => 'required|array',
            'application_ids.*' => 'exists:applications,id',
            'status' => 'required|in:menunggu,diterima,ditolak',
            'admin_notes' => 'nullable|string|max:1000'
        ]);

        Application::whereIn('id', $request->application_ids)->update([
            'status' => $request->status,
            'admin_notes' => $request->admin_notes,
            'reviewed_at' => now(),
            'reviewed_by' => Auth::id() ?? 1
        ]);

        // Send notifications to all affected users
        if ($request->status === 'diterima' || $request->status === 'ditolak') {
            $applications = Application::whereIn('id', $request->application_ids)->get();
            $statusForNotification = $request->status === 'diterima' ? 'verified' : 'rejected';
            
            foreach ($applications as $application) {
                $application->user->notify(new ApplicationVerified($application, $statusForNotification));
            }
        }

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diupdate secara bulk.');
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

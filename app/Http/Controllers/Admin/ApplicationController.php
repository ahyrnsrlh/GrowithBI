<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ApplicationController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = Application::with(['division']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter by division
        if ($request->filled('division')) {
            $query->where('division_id', $request->division);
        }

        // Search by name or email in applications table directly
        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
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
    public function show(Application $application)
    {
        $application->load(['division', 'user:id,name,email,phone,address,surat_pengantar_path,cv_path,motivation_letter_path,transkrip_path,ktp_path,npwp_path,buku_rekening_path,pas_foto_path']);

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
            'reviewed_by' => auth()->id() ?? 1 // For now, default to admin
        ]);

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
            'reviewed_by' => auth()->id() ?? 1
        ]);

        return redirect()->back()->with('success', 'Status pendaftaran berhasil diupdate secara bulk.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}

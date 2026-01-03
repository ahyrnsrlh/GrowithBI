<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DivisionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $divisions = Division::withCount([
                'applications',
                'applications as accepted_count' => function ($query) {
                    $query->where('status', 'diterima');
                }
            ])
            ->latest()
            ->get();

        $stats = [
            'total_divisions' => Division::count(),
            'active_divisions' => Division::where('is_active', true)->count(),
            'total_quota' => Division::sum('max_interns'),
            'total_applications' => Division::withCount('applications')->get()->sum('applications_count'),
        ];

        return Inertia::render('Admin/Divisions/Index', [
            'divisions' => $divisions,
            'stats' => $stats
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $supervisors = User::where('role', 'pembimbing')->select('id', 'name')->get();

        return Inertia::render('Admin/Divisions/Create', [
            'supervisors' => $supervisors
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'job_description' => 'nullable|array',
            'job_description.*' => 'nullable|string',
            'requirements' => 'nullable|array',
            'requirements.*' => 'nullable|string',
            'quota' => 'required|integer|min:1|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'application_deadline' => 'nullable|date',
            'selection_announcement' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        
        // Convert arrays to JSON for storage - ensure we have proper arrays
        if (isset($data['job_description']) && is_array($data['job_description'])) {
            // Filter out empty values and ensure we have proper array structure
            $job_descriptions = array_filter($data['job_description'], function($item) {
                return !empty(trim($item));
            });
            $data['job_description'] = json_encode(array_values($job_descriptions));
        }
        
        if (isset($data['requirements']) && is_array($data['requirements'])) {
            // Filter out empty values and ensure we have proper array structure
            $requirements = array_filter($data['requirements'], function($item) {
                return !empty(trim($item));
            });
            $data['requirements'] = json_encode(array_values($requirements));
        }

        Division::create($data);

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Division $division)
    {
        $division->load(['applications.user']);
        $division->loadCount('applications');

        $applications = $division->applications()->with('user')->latest()->get();

        return Inertia::render('Admin/Divisions/Show', [
            'division' => $division,
            'applications' => $applications
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Division $division)
    {
        $supervisors = User::where('role', 'pembimbing')->select('id', 'name')->get();

        return Inertia::render('Admin/Divisions/Edit', [
            'division' => $division,
            'supervisors' => $supervisors
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Division $division)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'job_description' => 'nullable|array',
            'job_description.*' => 'nullable|string',
            'requirements' => 'nullable|array',
            'requirements.*' => 'nullable|string',
            'quota' => 'required|integer|min:1|max:100',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'application_deadline' => 'nullable|date',
            'selection_announcement' => 'nullable|date',
            'is_active' => 'boolean'
        ]);

        $data = $request->all();
        
        // Convert arrays to JSON for storage - ensure we have proper arrays
        if (isset($data['job_description']) && is_array($data['job_description'])) {
            // Filter out empty values and ensure we have proper array structure
            $job_descriptions = array_filter($data['job_description'], function($item) {
                return !empty(trim($item));
            });
            $data['job_description'] = json_encode(array_values($job_descriptions));
        }
        
        if (isset($data['requirements']) && is_array($data['requirements'])) {
            // Filter out empty values and ensure we have proper array structure
            $requirements = array_filter($data['requirements'], function($item) {
                return !empty(trim($item));
            });
            $data['requirements'] = json_encode(array_values($requirements));
        }

        $division->update($data);

        return redirect()->route('admin.divisions.show', $division)
            ->with('success', 'Divisi berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Division $division)
    {
        // Check if division has applications
        if ($division->applications()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Divisi tidak dapat dihapus karena masih memiliki pendaftaran.');
        }

        // Check if division has logbooks
        if ($division->logbooks()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Divisi tidak dapat dihapus karena masih memiliki laporan harian (logbook) yang terkait.');
        }

        $division->delete();

        return redirect()->route('admin.divisions.index')
            ->with('success', 'Divisi berhasil dihapus.');
    }
}

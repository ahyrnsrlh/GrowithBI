<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class SupervisorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::where('role', 'pembimbing')
                ->withCount(['supervisedDivisions', 'supervisedApplications']);

            // Search
            if ($request->search) {
                $query->where(function ($q) use ($request) {
                    $q->where('name', 'like', '%' . $request->search . '%')
                      ->orWhere('email', 'like', '%' . $request->search . '%')
                      ->orWhere('phone', 'like', '%' . $request->search . '%');
                });
            }

            // Filter by status
            if ($request->status !== null) {
                $query->where('is_active', $request->status === 'active');
            }

            $supervisors = $query->latest()->paginate(10)->withQueryString();

            // Ensure all supervisors have required fields
            $supervisors->getCollection()->transform(function ($supervisor) {
                $supervisor->supervised_divisions_count = $supervisor->supervised_divisions_count ?? 0;
                $supervisor->supervised_applications_count = $supervisor->supervised_applications_count ?? 0;
                return $supervisor;
            });

            $stats = [
                'total_supervisors' => User::where('role', 'pembimbing')->count(),
                'active_supervisors' => User::where('role', 'pembimbing')->where('is_active', true)->count(),
                'total_divisions' => Division::whereNotNull('supervisor_id')->count(),
                'unassigned_divisions' => Division::whereNull('supervisor_id')->count(),
            ];

            return Inertia::render('Admin/Supervisors/Index', [
                'supervisors' => $supervisors,
                'stats' => $stats,
                'filters' => $request->only(['search', 'status'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error in SupervisorController@index: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat data pembimbing.');
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Supervisors/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'required|string|min:8|confirmed',
            'is_active' => 'boolean'
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'password' => Hash::make($request->password),
            'role' => 'pembimbing',
            'is_active' => $request->is_active ?? true,
        ]);

        return redirect()->route('admin.supervisors.index')
            ->with('success', 'Pembimbing berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $supervisor)
    {
        if ($supervisor->role !== 'pembimbing') {
            abort(404);
        }

        $supervisor->load(['supervisedDivisions.applications.user']);
        $supervisor->loadCount(['supervisedDivisions', 'supervisedApplications']);

        $divisions = $supervisor->supervisedDivisions()
            ->withCount('applications')
            ->get();

        return Inertia::render('Admin/Supervisors/Show', [
            'supervisor' => $supervisor,
            'divisions' => $divisions
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $supervisor)
    {
        if ($supervisor->role !== 'pembimbing') {
            abort(404);
        }

        return Inertia::render('Admin/Supervisors/Edit', [
            'supervisor' => $supervisor
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $supervisor)
    {
        if ($supervisor->role !== 'pembimbing') {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $supervisor->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'password' => 'nullable|string|min:8|confirmed',
            'is_active' => 'boolean'
        ]);

        $updateData = [
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => $request->is_active ?? true,
        ];

        if ($request->password) {
            $updateData['password'] = Hash::make($request->password);
        }

        $supervisor->update($updateData);

        return redirect()->route('admin.supervisors.show', $supervisor)
            ->with('success', 'Data pembimbing berhasil diupdate.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $supervisor)
    {
        if ($supervisor->role !== 'pembimbing') {
            abort(404);
        }

        // Check if supervisor has assigned divisions
        if ($supervisor->supervisedDivisions()->count() > 0) {
            return redirect()->back()
                ->with('error', 'Pembimbing tidak dapat dihapus karena masih memiliki divisi yang dibimbing.');
        }

        $supervisor->delete();

        return redirect()->route('admin.supervisors.index')
            ->with('success', 'Pembimbing berhasil dihapus.');
    }
}

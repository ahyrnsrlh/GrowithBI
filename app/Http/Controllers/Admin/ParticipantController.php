<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\Division;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Log;

class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $query = User::where('role', 'peserta')
                ->with(['applications.division']);

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

            // Filter by division
            if ($request->division) {
                $query->whereHas('applications', function ($q) use ($request) {
                    $q->where('division_id', $request->division)
                      ->where('status', 'diterima');
                });
            }

            $participants = $query->latest()->paginate(10)->withQueryString();

            // Ensure all participants have required fields and relationships
            $participants->getCollection()->transform(function ($participant) {
                $participant->applications = $participant->applications ?? collect();
                $participant->is_active = $participant->is_active ?? true;
                return $participant;
            });

            $stats = [
                'total_participants' => User::where('role', 'peserta')->count(),
                'active_participants' => Application::where('status', 'diterima')->distinct('user_id')->count(),
                'total_applications' => Application::count(),
                'completed_participants' => 0, // Will be calculated based on logbooks completion later
            ];

            $divisions = Division::where('is_active', true)->select('id', 'name')->get();

            return Inertia::render('Admin/Participants/Index', [
                'participants' => $participants,
                'stats' => $stats,
                'divisions' => $divisions,
                'filters' => $request->only(['search', 'status', 'division'])
            ]);
        } catch (\Exception $e) {
            Log::error('Error in ParticipantController@index: ' . $e->getMessage());
            return redirect()->route('admin.dashboard')
                ->with('error', 'Terjadi kesalahan saat memuat data peserta.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(User $participant)
    {
        if ($participant->role !== 'peserta') {
            abort(404);
        }

        $participant->load(['applications.division']);

        $applications = $participant->applications()
            ->with('division')
            ->latest()
            ->get();

        return Inertia::render('Admin/Participants/Show', [
            'participant' => $participant,
            'applications' => $applications
        ]);
    }

    /**
     * Update participant status.
     */
    public function updateStatus(Request $request, User $participant)
    {
        if ($participant->role !== 'peserta') {
            abort(404);
        }

        $request->validate([
            'is_active' => 'required|boolean'
        ]);

        $participant->update([
            'is_active' => $request->is_active
        ]);

        return redirect()->back()
            ->with('success', 'Status peserta berhasil diupdate.');
    }
}

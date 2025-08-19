<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use App\Models\Logbook;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is peserta
        if (!auth()->check() || auth()->user()->role !== 'peserta') {
            abort(403, 'Unauthorized access. Peserta role required.');
        }

        $user = Auth::user();
        
        // Get user's applications
        $applications = Application::where('user_id', $user->id)
            ->with(['division'])
            ->latest()
            ->get();

        // Get user's logbooks through applications
        $logbooks = $user->logbooks()
            ->latest()
            ->take(10)
            ->get();

        // Get available divisions for application
        $availableDivisions = Division::where('is_active', true)
            ->with(['supervisor'])
            ->get();

        $stats = [
            'total_applications' => $applications->count(),
            'pending_applications' => $applications->where('status', 'menunggu')->count(),
            'accepted_applications' => $applications->where('status', 'diterima')->count(),
            'total_logbooks' => $logbooks->count(),
            'current_division' => $applications->where('status', 'diterima')->first()?->division->name ?? null,
            'supervisor_name' => $user->supervisor->name ?? 'Belum ditentukan'
        ];

        return Inertia::render('Peserta/Dashboard', [
            'stats' => $stats,
            'applications' => $applications,
            'recentLogbooks' => $logbooks,
            'availableDivisions' => $availableDivisions,
            'user' => $user->load(['supervisor'])
        ]);
    }
}

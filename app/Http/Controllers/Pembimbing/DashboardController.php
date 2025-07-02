<?php

namespace App\Http\Controllers\Pembimbing;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is pembimbing
        if (!auth()->check() || auth()->user()->role !== 'pembimbing') {
            abort(403, 'Unauthorized access. Pembimbing role required.');
        }

        $user = Auth::user();
        
        // Get divisions supervised by this pembimbing
        $supervisedDivisions = Division::where('supervisor_id', $user->id)->get();
        
        // Get participants under supervision
        $participants = User::where('role', 'peserta')
            ->where('supervisor_id', $user->id)
            ->with(['applications', 'logbooks'])
            ->get();
        
        // Get applications for supervised divisions
        $applications = Application::whereIn('division_id', $supervisedDivisions->pluck('id'))
            ->with(['user', 'division'])
            ->latest()
            ->take(10)
            ->get();

        $stats = [
            'total_participants' => $participants->count(),
            'active_participants' => $participants->where('is_active', true)->count(),
            'total_divisions' => $supervisedDivisions->count(),
            'pending_applications' => $applications->where('status', 'menunggu')->count(),
            'recent_logbooks' => $participants->sum(function($participant) {
                return $participant->logbooks->count();
            })
        ];

        return Inertia::render('Pembimbing/Dashboard', [
            'stats' => $stats,
            'participants' => $participants,
            'recentApplications' => $applications,
            'supervisedDivisions' => $supervisedDivisions
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Division;
use App\Models\User;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        // Real data from database
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'menunggu')->count(),
            'accepted_applications' => Application::where('status', 'diterima')->count(),
            'rejected_applications' => Application::where('status', 'ditolak')->count(),
            'total_divisions' => Division::count(),
            'active_divisions' => Division::where('is_active', true)->count(),
            'total_supervisors' => User::where('role', 'pembimbing')->count(),
            'total_participants' => User::where('role', 'peserta')->count(),
        ];

        $recentApplications = Application::with(['user', 'division'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'name' => $application->user->name,
                    'email' => $application->user->email,
                    'division' => $application->division->name,
                    'status' => $application->status,
                    'applied_at' => $application->created_at->format('Y-m-d H:i:s')
                ];
            });

        $divisionsData = Division::with(['supervisor'])
            ->withCount([
                'applications',
                'applications as accepted_count' => function ($query) {
                    $query->where('status', 'diterima');
                }
            ])
            ->get()
            ->map(function ($division) {
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'quota' => $division->quota,
                    'applications' => $division->applications_count,
                    'accepted' => $division->accepted_count,
                    'supervisor' => $division->supervisor ? $division->supervisor->name : 'Belum ditentukan'
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'divisionsData' => $divisionsData
        ]);
    }
}

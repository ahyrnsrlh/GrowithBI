<?php

namespace App\Http\Controllers\Peserta;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Logbook;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        // Get user's applications
        $applications = Application::where('user_id', $user->id)
            ->with('division')
            ->orderBy('created_at', 'desc')
            ->get();

        // Get user's recent logbooks (only if they have accepted application)
        $recentLogbooks = collect();
        $acceptedApplication = $applications->where('status', 'diterima')->first();
        
        if ($acceptedApplication) {
            $recentLogbooks = Logbook::where('user_id', $user->id)
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
        }

        // Calculate statistics
        $stats = [
            'total_applications' => $applications->count(),
            'pending_applications' => $applications->where('status', 'diproses')->count(),
            'accepted_applications' => $applications->where('status', 'diterima')->count(),
            'total_logbooks' => $recentLogbooks->count(),
            'current_division' => $acceptedApplication ? $acceptedApplication->division : null,
            'supervisor_name' => $acceptedApplication && $acceptedApplication->division 
                ? 'GrowithBI Admin'
                : null,
        ];

        // Get available divisions for quick apply
        $availableDivisions = Division::where('is_active', true)
            ->whereDoesntHave('applications', function($query) use ($user) {
                $query->where('user_id', $user->id)
                      ->whereIn('status', ['diproses', 'diterima']);
            })
            ->get();

        return Inertia::render('Peserta/Dashboard', [
            'stats' => $stats,
            'applications' => $applications->map(function ($application) {
                return [
                    'id' => $application->id,
                    'status' => $application->status,
                    'created_at' => $application->created_at,
                    'updated_at' => $application->updated_at,
                    'division' => $application->division ? [
                        'id' => $application->division->id,
                        'name' => $application->division->name,
                        'description' => $application->division->description
                    ] : null
                ];
            }),
            'recentLogbooks' => $recentLogbooks->map(function ($logbook) {
                return [
                    'id' => $logbook->id,
                    'title' => $logbook->title,
                    'date' => $logbook->date,
                    'duration' => $logbook->duration,
                    'status' => $logbook->status,
                    'created_at' => $logbook->created_at
                ];
            }),
            'availableDivisions' => $availableDivisions,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
                'has_accepted_application' => $acceptedApplication !== null
            ]
        ]);
    }
}

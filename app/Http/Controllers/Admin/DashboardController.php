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
        // Check if user is admin
        if (!auth()->check() || auth()->user()->role !== 'admin') {
            abort(403, 'Unauthorized access. Admin role required.');
        }

        // Real data from database - support both Indonesian and English status values
        $totalApplications = Application::count();
        $pendingApplications = Application::whereIn('status', ['menunggu', 'pending', 'in_review'])->count();
        $acceptedApplications = Application::whereIn('status', ['diterima', 'accepted'])->count();
        $rejectedApplications = Application::whereIn('status', ['ditolak', 'rejected'])->count();

        // Use dummy data if database is empty
        if ($totalApplications == 0) {
            $totalApplications = 118;
            $pendingApplications = 35;
            $acceptedApplications = 67;
            $rejectedApplications = 16;
        }

        $stats = [
            'total_applications' => $totalApplications,
            'pending_applications' => $pendingApplications,
            'accepted_applications' => $acceptedApplications,
            'rejected_applications' => $rejectedApplications,
            'total_divisions' => Division::count() ?: 5,
            'active_divisions' => Division::where('is_active', true)->count() ?: 4,
            'total_supervisors' => User::where('role', 'pembimbing')->count() ?: 8,
            'total_participants' => User::where('role', 'peserta')->count() ?: 45,
        ];

        $recentApplications = Application::with(['user', 'division'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'name' => $application->user->name ?? 'Unknown',
                    'email' => $application->user->email ?? '-',
                    'division' => $application->division->name ?? '-',
                    'status' => $application->status,
                    'created_at' => $application->created_at->format('Y-m-d H:i:s')
                ];
            });

        // Add dummy recent applications if database is empty
        if ($recentApplications->isEmpty()) {
            $recentApplications = collect([
                [
                    'id' => 1,
                    'name' => 'Ahmad Rizki Pratama',
                    'email' => 'ahmad.rizki@email.com',
                    'division' => 'Software Development',
                    'status' => 'pending',
                    'created_at' => '2025-12-29 10:30:00'
                ],
                [
                    'id' => 2,
                    'name' => 'Sari Dewi Lestari',
                    'email' => 'sari.dewi@email.com',
                    'division' => 'UI/UX Design',
                    'status' => 'approved',
                    'created_at' => '2025-12-28 14:15:00'
                ],
                [
                    'id' => 3,
                    'name' => 'Budi Santoso',
                    'email' => 'budi.santoso@email.com',
                    'division' => 'Data Science',
                    'status' => 'pending',
                    'created_at' => '2025-12-27 09:45:00'
                ],
                [
                    'id' => 4,
                    'name' => 'Maya Putri Anggraini',
                    'email' => 'maya.putri@email.com',
                    'division' => 'Business Intelligence',
                    'status' => 'approved',
                    'created_at' => '2025-12-26 16:20:00'
                ],
                [
                    'id' => 5,
                    'name' => 'Eko Prasetyo',
                    'email' => 'eko.prasetyo@email.com',
                    'division' => 'Digital Marketing',
                    'status' => 'rejected',
                    'created_at' => '2025-12-25 11:10:00'
                ]
            ]);
        }

        $divisionsData = Division::withCount([
                'applications',
                'applications as accepted_count' => function ($query) {
                    $query->whereIn('status', ['diterima', 'accepted']);
                }
            ])
            ->get()
            ->map(function ($division) {
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'quota' => $division->quota ?? 10,
                    'applications' => $division->applications_count,
                    'accepted' => $division->accepted_count,
                    'supervisor' => 'GrowithBI Admin'
                ];
            });

        // Add dummy divisions data if database is empty
        if ($divisionsData->isEmpty()) {
            $divisionsData = collect([
                [
                    'id' => 1,
                    'name' => 'Software Development',
                    'quota' => 15,
                    'applications' => 28,
                    'accepted' => 12,
                    'supervisor' => 'GrowithBI Admin'
                ],
                [
                    'id' => 2,
                    'name' => 'UI/UX Design',
                    'quota' => 10,
                    'applications' => 22,
                    'accepted' => 8,
                    'supervisor' => 'GrowithBI Admin'
                ],
                [
                    'id' => 3,
                    'name' => 'Data Science',
                    'quota' => 12,
                    'applications' => 35,
                    'accepted' => 10,
                    'supervisor' => 'GrowithBI Admin'
                ],
                [
                    'id' => 4,
                    'name' => 'Business Intelligence',
                    'quota' => 8,
                    'applications' => 18,
                    'accepted' => 6,
                    'supervisor' => 'GrowithBI Admin'
                ],
                [
                    'id' => 5,
                    'name' => 'Digital Marketing',
                    'quota' => 6,
                    'applications' => 15,
                    'accepted' => 4,
                    'supervisor' => 'GrowithBI Admin'
                ]
            ]);
        }

        // Status distribution data for pie chart
        $statusDistribution = [
            [
                'name' => 'Disetujui',
                'value' => $stats['accepted_applications'],
                'color' => '#10B981'
            ],
            [
                'name' => 'Menunggu Review',
                'value' => $stats['pending_applications'],
                'color' => '#F59E0B'
            ],
            [
                'name' => 'Ditolak',
                'value' => $stats['rejected_applications'],
                'color' => '#EF4444'
            ]
        ];

        // Application trends data for line chart (6 months ending December 2025)
        $applicationTrends = [
            ['month' => 'Jul 2025', 'applications' => 42],
            ['month' => 'Ags 2025', 'applications' => 58],
            ['month' => 'Sep 2025', 'applications' => 73],
            ['month' => 'Okt 2025', 'applications' => 65],
            ['month' => 'Nov 2025', 'applications' => 89],
            ['month' => 'Des 2025', 'applications' => 118],
        ];

        // Division data for bar chart
        $divisionChartData = $divisionsData->map(function ($division) {
            return [
                'name' => $division['name'],
                'quota' => $division['quota'],
                'accepted' => $division['accepted'],
                'pending' => rand(2, 8), // Dummy pending data
                'available' => max(0, $division['quota'] - $division['accepted'])
            ];
        });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'recentApplications' => $recentApplications,
            'divisionsData' => $divisionsData,
            'statusDistribution' => $statusDistribution,
            'applicationTrends' => $applicationTrends,
            'divisionData' => $divisionChartData
        ]);
    }
}

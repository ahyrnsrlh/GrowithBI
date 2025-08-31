<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Application;
use App\Models\Logbook;
use App\Models\Division;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class AdminController extends Controller
{
    public function dashboard()
    {
        // Basic statistics
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'accepted_applications' => Application::where('status', 'approved')->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'active_divisions' => Division::where('is_active', true)->count(),
            'total_participants' => User::where('role', 'peserta')->where('is_active', true)->count(),
        ];

        // Application status distribution for pie chart
        $statusDistribution = [
            ['name' => 'Menunggu', 'value' => $stats['pending_applications'], 'color' => '#F59E0B'],
            ['name' => 'Diterima', 'value' => $stats['accepted_applications'], 'color' => '#10B981'],
            ['name' => 'Ditolak', 'value' => $stats['rejected_applications'], 'color' => '#EF4444'],
        ];

        // Application trends over last 6 months
        $applicationTrends = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $count = Application::whereYear('created_at', $date->year)
                ->whereMonth('created_at', $date->month)
                ->count();
            
            $applicationTrends[] = [
                'month' => $date->format('M Y'),
                'applications' => $count
            ];
        }

        // Division capacity and fulfillment
        $divisionData = Division::where('is_active', true)
            ->withCount([
                'applications',
                'applications as pending_count' => function ($query) {
                    $query->where('status', 'pending');
                },
                'applications as accepted_count' => function ($query) {
                    $query->where('status', 'approved');
                }
            ])
            ->get()
            ->map(function ($division) {
                return [
                    'name' => $division->name,
                    'quota' => $division->quota,
                    'pending' => $division->pending_count,
                    'accepted' => $division->accepted_count,
                    'available' => max(0, $division->quota - $division->accepted_count)
                ];
            });

        // Recent applications for table
        $recentApplications = Application::with(['user', 'division'])
            ->latest()
            ->take(5)
            ->get()
            ->map(function ($application) {
                return [
                    'id' => $application->id,
                    'name' => $application->user->name ?? $application->name,
                    'email' => $application->user->email ?? $application->email,
                    'division' => $application->division->name ?? 'N/A',
                    'status' => $application->status,
                    'created_at' => $application->created_at->format('d M Y'),
                ];
            });

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats,
            'statusDistribution' => $statusDistribution,
            'applicationTrends' => $applicationTrends,
            'divisionData' => $divisionData,
            'recentApplications' => $recentApplications,
        ]);
    }

    public function manageUsers(Request $request)
    {
        $query = User::query();

        // Apply filters
        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('status')) {
            $isActive = $request->status === 'active';
            $query->where('is_active', $isActive);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();
        
        return Inertia::render('Admin/Users', [
            'users' => $users
        ]);
    }

    public function manageApplications(Request $request)
    {
        $query = Application::with(['division']);

        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('division_id')) {
            $query->where('division_id', $request->division_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $applications = $query->latest()->paginate(10)->withQueryString();
        
        // Get stats
        $stats = [
            'total' => Application::count(),
            'pending' => Application::where('status', 'pending')->count(),
            'approved' => Application::where('status', 'approved')->count(),
            'rejected' => Application::where('status', 'rejected')->count(),
        ];

        // Get divisions for filter
        $divisions = \App\Models\Division::where('is_active', true)->get();
        
        return Inertia::render('Admin/Applications', [
            'applications' => $applications,
            'stats' => $stats,
            'divisions' => $divisions
        ]);
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent admin from deactivating themselves
        if ($user->id === Auth::id()) {
            return back()->withErrors(['error' => 'Anda tidak dapat menonaktifkan akun Anda sendiri.']);
        }
        
        $user->update([
            'is_active' => !$user->is_active
        ]);

        $message = $user->is_active ? 'Pengguna berhasil diaktifkan.' : 'Pengguna berhasil dinonaktifkan.';
        
        return back()->with('message', $message);
    }

    public function approveApplication(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'approved',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('message', 'Application approved successfully.');
    }

    public function rejectApplication(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        $application->update([
            'status' => 'rejected',
            'approved_by' => Auth::id(),
            'approved_at' => now(),
        ]);

        return back()->with('message', 'Application rejected.');
    }

    public function showApplication($id)
    {
        $application = Application::with(['division'])->findOrFail($id);
        
        return Inertia::render('Admin/Applications/Show', [
            'application' => $application
        ]);
    }

    public function manageDivisions(Request $request)
    {
        $query = Division::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where('name', 'like', "%{$search}%");
        }

        $divisions = $query->latest()->paginate(10)->withQueryString();
        
        return Inertia::render('Admin/Divisions', [
            'divisions' => $divisions
        ]);
    }

    public function createDivision()
    {
        return Inertia::render('Admin/Divisions/Create');
    }

    public function storeDivision(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'max_interns' => 'required|integer|min:1',
        ]);

        Division::create($request->all());

        return redirect()->route('admin.divisions.index')
            ->with('message', 'Division created successfully.');
    }

    public function editDivision($id)
    {
        $division = Division::findOrFail($id);
        
        return Inertia::render('Admin/Divisions/Edit', [
            'division' => $division,
        ]);
    }

    public function updateDivision(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'max_interns' => 'required|integer|min:1',
        ]);

        $division->update($request->all());

        return redirect()->route('admin.divisions.index')
            ->with('message', 'Division updated successfully.');
    }

    public function deleteDivision($id)
    {
        $division = Division::findOrFail($id);
        $division->delete();

        return back()->with('message', 'Division deleted successfully.');
    }

    public function showDivision($id)
    {
        $division = Division::with(['applications'])->findOrFail($id);
        
        return Inertia::render('Admin/Divisions/Show', [
            'division' => $division
        ]);
    }

    public function manageParticipants(Request $request)
    {
        $query = User::where('role', 'peserta');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $participants = $query->latest()->paginate(10)->withQueryString();
        $supervisors = User::where('role', 'pembimbing')->get();
        
        return Inertia::render('Admin/Participants', [
            'participants' => $participants,
            'supervisors' => $supervisors
        ]);
    }

    public function createParticipant()
    {
        return Inertia::render('Admin/Participants/Create');
    }

    public function storeParticipant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peserta',
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.participants.index')
            ->with('message', 'Participant created successfully.');
    }

    public function editParticipant($id)
    {
        $participant = User::where('role', 'peserta')->findOrFail($id);
        
        return Inertia::render('Admin/Participants/Edit', [
            'participant' => $participant,
        ]);
    }

    public function updateParticipant(Request $request, $id)
    {
        $participant = User::where('role', 'peserta')->findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $updateData = $request->only(['name', 'email', 'phone', 'address']);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $updateData['password'] = Hash::make($request->password);
        }

        $participant->update($updateData);

        return redirect()->route('admin.participants.index')
            ->with('message', 'Participant updated successfully.');
    }

    public function showParticipant($id)
    {
        $participant = User::where('role', 'peserta')
            ->with(['logbooks'])
            ->findOrFail($id);
        
        return Inertia::render('Admin/Participants/Show', [
            'participant' => $participant
        ]);
    }

    public function reports()
    {
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'approved_applications' => Application::where('status', 'approved')->count(),
            'rejected_applications' => Application::where('status', 'rejected')->count(),
            'total_participants' => User::where('role', 'peserta')->count(),
            'active_participants' => User::where('role', 'peserta')->where('is_active', true)->count(),
            'total_supervisors' => User::where('role', 'pembimbing')->count(),
            'total_divisions' => Division::count(),
            'total_logbooks' => Logbook::count(),
        ];
        
        return Inertia::render('Admin/Reports', [
            'stats' => $stats
        ]);
    }

    public function applicationsReport()
    {
        $applications = Application::with(['division'])
            ->selectRaw('status, COUNT(*) as count')
            ->groupBy('status')
            ->get();
        
        return Inertia::render('Admin/Reports/Applications', [
            'applications' => $applications
        ]);
    }

    public function participantsReport()
    {
        $participants = User::where('role', 'peserta')
            ->get();
        
        return Inertia::render('Admin/Reports/Participants', [
            'participants' => $participants
        ]);
    }

    public function logbooksReport()
    {
        $logbooks = Logbook::with(['user'])
            ->selectRaw('user_id, COUNT(*) as count')
            ->groupBy('user_id')
            ->get();
        
        return Inertia::render('Admin/Reports/Logbooks', [
            'logbooks' => $logbooks
        ]);
    }

    public function exportReport()
    {
        // Export functionality can be implemented here
        return back()->with('message', 'Export functionality will be implemented soon.');
    }

    // User management methods
    public function createUser()
    {
        return Inertia::render('Admin/Users/Create');
    }

    public function storeUser(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'role' => 'required|in:admin,pembimbing,peserta',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.users.index')
            ->with('message', 'User created successfully.');
    }

    public function editUser($id)
    {
        $user = User::findOrFail($id);
        
        return Inertia::render('Admin/Users/Edit', [
            'user' => $user
        ]);
    }

    public function updateUser(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'role' => 'required|in:admin,pembimbing,peserta',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
        ]);

        $updateData = $request->only(['name', 'email', 'role', 'phone', 'address']);
        
        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:8']);
            $updateData['password'] = Hash::make($request->password);
        }

        $user->update($updateData);

        return redirect()->route('admin.users.index')
            ->with('message', 'User updated successfully.');
    }

    public function deleteUser($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent deleting the last admin
        if ($user->role === 'admin' && User::where('role', 'admin')->count() <= 1) {
            return back()->withErrors(['error' => 'Cannot delete the last admin user.']);
        }
        
        $user->delete();

        return back()->with('message', 'User deleted successfully.');
    }
}

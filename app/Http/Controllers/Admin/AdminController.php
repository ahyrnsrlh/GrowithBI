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
        $stats = [
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'active_interns' => User::where('role', 'peserta')->where('is_active', true)->count(),
            'supervisors' => User::where('role', 'pembimbing')->count(),
            'recent_logbooks' => Logbook::with('user')->latest()->take(5)->get(),
        ];

        return Inertia::render('Admin/Dashboard', [
            'stats' => $stats
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
        $query = Division::with('supervisor');

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
        $supervisors = User::where('role', 'pembimbing')->get();
        
        return Inertia::render('Admin/Divisions/Create', [
            'supervisors' => $supervisors
        ]);
    }

    public function storeDivision(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'quota' => 'required|integer|min:1',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        Division::create($request->all());

        return redirect()->route('admin.divisions.index')
            ->with('message', 'Division created successfully.');
    }

    public function editDivision($id)
    {
        $division = Division::findOrFail($id);
        $supervisors = User::where('role', 'pembimbing')->get();
        
        return Inertia::render('Admin/Divisions/Edit', [
            'division' => $division,
            'supervisors' => $supervisors
        ]);
    }

    public function updateDivision(Request $request, $id)
    {
        $division = Division::findOrFail($id);
        
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'requirements' => 'nullable|string',
            'quota' => 'required|integer|min:1',
            'supervisor_id' => 'nullable|exists:users,id',
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
        $division = Division::with(['supervisor', 'applications'])->findOrFail($id);
        
        return Inertia::render('Admin/Divisions/Show', [
            'division' => $division
        ]);
    }

    public function manageSupervisors(Request $request)
    {
        $query = User::where('role', 'pembimbing');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $supervisors = $query->latest()->paginate(10)->withQueryString();
        
        return Inertia::render('Admin/Supervisors', [
            'supervisors' => $supervisors
        ]);
    }

    public function createSupervisor()
    {
        return Inertia::render('Admin/Supervisors/Create');
    }

    public function storeSupervisor(Request $request)
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
            'role' => 'pembimbing',
            'phone' => $request->phone,
            'address' => $request->address,
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        return redirect()->route('admin.supervisors.index')
            ->with('message', 'Supervisor created successfully.');
    }

    public function editSupervisor($id)
    {
        $supervisor = User::where('role', 'pembimbing')->findOrFail($id);
        
        return Inertia::render('Admin/Supervisors/Edit', [
            'supervisor' => $supervisor
        ]);
    }

    public function updateSupervisor(Request $request, $id)
    {
        $supervisor = User::where('role', 'pembimbing')->findOrFail($id);
        
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

        $supervisor->update($updateData);

        return redirect()->route('admin.supervisors.index')
            ->with('message', 'Supervisor updated successfully.');
    }

    public function showSupervisor($id)
    {
        $supervisor = User::where('role', 'pembimbing')
            ->with(['supervisedInterns'])
            ->findOrFail($id);
        
        return Inertia::render('Admin/Supervisors/Show', [
            'supervisor' => $supervisor
        ]);
    }

    public function manageParticipants(Request $request)
    {
        $query = User::where('role', 'peserta')->with(['supervisor']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        if ($request->filled('supervisor_id')) {
            $query->where('supervisor_id', $request->supervisor_id);
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
        $supervisors = User::where('role', 'pembimbing')->get();
        
        return Inertia::render('Admin/Participants/Create', [
            'supervisors' => $supervisors
        ]);
    }

    public function storeParticipant(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8',
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string',
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'peserta',
            'supervisor_id' => $request->supervisor_id,
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
        $supervisors = User::where('role', 'pembimbing')->get();
        
        return Inertia::render('Admin/Participants/Edit', [
            'participant' => $participant,
            'supervisors' => $supervisors
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
            'supervisor_id' => 'nullable|exists:users,id',
        ]);

        $updateData = $request->only(['name', 'email', 'phone', 'address', 'supervisor_id']);
        
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
            ->with(['supervisor', 'logbooks'])
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
            ->with(['supervisor'])
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

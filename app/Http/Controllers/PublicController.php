<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function divisions()
    {
        $divisions = Division::where('is_active', true)
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
                    'description' => $division->description,
                    'requirements' => $division->requirements,
                    'quota' => $division->quota,
                    'current_interns' => $division->accepted_count,
                    'available_slots' => $division->quota - $division->accepted_count,
                    'supervisor' => 'GrowithBI Admin',
                    'supervisor_email' => 'admin@growithbi.com',
                ];
            });

        return Inertia::render('Public/Divisions', [
            'divisions' => $divisions
        ]);
    }

    public function divisionDetail(Division $division)
    {
        try {
            // Log untuk debug
            Log::info('DivisionDetail accessed', [
                'division_id' => $division->id,
                'division_name' => $division->name,
                'is_active' => $division->is_active
            ]);

            // Check if division is active
            if (!$division->is_active) {
                abort(404, 'Division not found or inactive');
            }

            // Check if user already has a pending or accepted application
            $existingApplication = null;
            if (Auth::check()) {
                $existingApplication = Application::where('email', Auth::user()->email)
                    ->whereIn('status', ['menunggu', 'diterima'])
                    ->with('division')
                    ->first();
            }

            // Ensure division data is complete
            $divisionData = [
                'id' => $division->id,
                'name' => $division->name,
                'description' => $division->description,
                'job_description' => $division->job_description,
                'requirements' => $division->requirements,
                'quota' => $division->quota ?? $division->max_interns,
                'current_interns' => $division->applications()->where('status', 'diterima')->count(),
                'available_slots' => ($division->quota ?? $division->max_interns) - $division->applications()->where('status', 'diterima')->count(),
                'start_date' => $division->start_date,
                'end_date' => $division->end_date,
                'application_deadline' => $division->application_deadline,
                'selection_announcement' => $division->selection_announcement,
                'is_active' => $division->is_active,
            ];

            return Inertia::render('Public/DivisionDetail', [
                'division' => $divisionData,
                'existingApplication' => $existingApplication,
                'auth' => [
                    'user' => Auth::user()
                ]
            ]);
        } catch (\Exception $e) {
            Log::error('Error in divisionDetail', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            abort(500, 'Internal server error');
        }
    }

    public function checkStatus()
    {
        return Inertia::render('Public/CheckStatus');
    }

    public function searchStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string'
        ]);

        $application = Application::where('email', $request->email)
            ->where('phone', $request->phone)
            ->with('division')
            ->first();

        if (!$application) {
            return back()->withErrors(['message' => 'Data tidak ditemukan']);
        }

        return Inertia::render('Public/ApplicationStatus', [
            'application' => $application
        ]);
    }

    public function quickRegister(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id'
        ]);

        $user = $request->user();
        
        // Check if user already has a pending or accepted application
        $existingApplication = Application::where('email', $user->email)
            ->whereIn('status', ['menunggu', 'diterima'])
            ->with('division')
            ->first();

        if ($existingApplication) {
            $message = $existingApplication->status === 'diterima' 
                ? 'Anda sudah diterima di divisi ' . $existingApplication->division->name . '. Anda tidak bisa mendaftar ke divisi lain.'
                : 'Anda sudah memiliki pendaftaran yang sedang diproses di divisi ' . $existingApplication->division->name . '. Tunggu hasil proses tersebut sebelum mendaftar ke divisi lain.';
            
            return response()->json([
                'success' => false,
                'message' => $message
            ]);
        }

        Application::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'address' => $user->address ?? '',
            'division_id' => $request->division_id,
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil! Kami akan menghubungi Anda segera.'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use App\Events\NewApplicationSubmitted;
use Inertia\Inertia;

class PublicController extends Controller
{
    public function divisions()
    {
        $quotaService = app(\App\Services\DivisionQuotaService::class);
        $divisions = Division::where('is_active', true)
            ->get()
            ->map(function ($division) use ($quotaService) {
                $acceptedCount = $quotaService->acceptedCount($division);
                $remaining = $quotaService->remainingQuota($division);
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'description' => $division->description,
                    'requirements' => $division->requirements,
                    'quota' => $division->max_interns,
                    'current_interns' => $acceptedCount,
                    'available_slots' => $remaining,
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
                // Optimized query with specific columns and indexed fields
                $existingApplication = Application::select('id', 'user_id', 'division_id', 'status', 'created_at', 'email')
                    ->where('user_id', Auth::id()) // Using user_id index instead of email
                    ->whereIn('status', ['menunggu', 'diterima'])
                    ->with('division:id,name') // Only load necessary fields
                    ->first();
            }

            // Optimized: Use single query with count aggregate
            $quotaService = app(\App\Services\DivisionQuotaService::class);
            $acceptedCount = $quotaService->acceptedCount($division);
            $remaining = $quotaService->remainingQuota($division);
            
            $quota = $division->quota ?? $division->max_interns;

            // Ensure division data is complete
            $divisionData = [
                'id' => $division->id,
                'name' => $division->name,
                'description' => $division->description,
                'job_description' => $division->job_description,
                'requirements' => $division->requirements,
                'quota' => $quota,
                'current_interns' => $acceptedCount,
                'available_slots' => $remaining,
                'start_date' => $division->start_date,
                'end_date' => $division->end_date,
                'application_deadline' => $division->application_deadline,
                'selection_announcement' => $division->selection_announcement,
                'is_active' => $division->is_active,
                'benefit' => $division->benefit,
                'criteria' => $division->criteria,
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
        
        $division = Division::findOrFail($request->division_id);
        $quotaService = app(\App\Services\DivisionQuotaService::class);
        if (!$quotaService->hasAvailableQuota($division)) {
            return response()->json([
                'success' => false,
                'message' => 'Divisi yang dipilih telah mencapai batas kuota peserta. Silakan pilih divisi lain.'
            ]);
        }

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

        $application = Application::create([
            'user_id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone ?? '',
            'address' => $user->address ?? '',
            'division_id' => $request->division_id,
            'status' => 'menunggu',
        ]);

        NewApplicationSubmitted::dispatch($application);

        return response()->json([
            'success' => true,
            'message' => 'Pendaftaran berhasil! Kami akan menghubungi Anda segera.'
        ]);
    }
}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Division;
use App\Models\Application;
use Illuminate\Support\Facades\Auth;
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

    public function applicationForm()
    {
        $divisions = Division::where('is_active', true)
            ->get();

        // Check if user already has a pending or accepted application
        $existingApplication = null;
        if (Auth::check()) {
            $existingApplication = Application::where('email', Auth::user()->email)
                ->whereIn('status', ['menunggu', 'diterima'])
                ->with('division')
                ->first();
        }

        return Inertia::render('Public/ApplicationForm', [
            'divisions' => $divisions,
            'existingApplication' => $existingApplication
        ]);
    }

    public function storeApplication(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'email', 'max:255', new \App\Rules\UniqueActiveApplication($request->email)],
            'phone' => 'required|string|max:20',
            'address' => 'required|string',
            'division_id' => 'required|exists:divisions,id',
            'cv_url' => 'nullable|url',
            'cover_letter_url' => 'nullable|url',
            'portfolio_url' => 'nullable|url',
            'motivation' => 'required|string|min:50',
        ]);

        Application::create([
            'name' => $request->name,
            'email' => $request->email,
            'phone' => $request->phone,
            'address' => $request->address,
            'division_id' => $request->division_id,
            'cv_url' => $request->cv_url,
            'cover_letter_url' => $request->cover_letter_url,
            'portfolio_url' => $request->portfolio_url,
            'motivation' => $request->motivation,
            'status' => 'menunggu',
        ]);

        return redirect()->back()->with('message', 'Aplikasi berhasil dikirim! Kami akan menghubungi Anda segera.');
    }

    public function divisionDetail(Division $division)
    {
        // Check if user already has a pending or accepted application
        $existingApplication = null;
        if (Auth::check()) {
            $existingApplication = Application::where('email', Auth::user()->email)
                ->whereIn('status', ['menunggu', 'diterima'])
                ->with('division')
                ->first();
        }

        return Inertia::render('Public/DivisionDetail', [
            'division' => $division,
            'existingApplication' => $existingApplication,
            'auth' => [
                'user' => Auth::user()
            ]
        ]);
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

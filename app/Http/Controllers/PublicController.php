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
            ->with(['supervisor'])
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
                    'supervisor' => $division->supervisor ? $division->supervisor->name : 'Belum ditentukan',
                    'supervisor_email' => $division->supervisor ? $division->supervisor->email : null,
                ];
            });

        return Inertia::render('Public/Divisions', [
            'divisions' => $divisions
        ]);
    }

    public function applicationForm()
    {
        $divisions = Division::where('is_active', true)
            ->with(['supervisor'])
            ->get();

        return Inertia::render('Public/ApplicationForm', [
            'divisions' => $divisions
        ]);
    }

    public function storeApplication(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
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
        return Inertia::render('Public/DivisionDetail', [
            'division' => $division->load('supervisor'),
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
        
        // Check if user already has application for this division
        $existingApplication = Application::where('email', $user->email)
            ->where('division_id', $request->division_id)
            ->first();

        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah mendaftar untuk divisi ini'
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

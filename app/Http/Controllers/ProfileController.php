<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use App\Models\Application;
use App\Models\Division;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

class ProfileController extends Controller
{
    /**
     * Display the user's profile page.
     */
    public function index(Request $request): Response
    {
        $user = $request->user()->fresh(); // Ensure we get fresh user data
        
        // Get user's applications with division and status
        $applications = Application::where('user_id', $user->id)
            ->with(['division'])
            ->latest()
            ->get();

        // Get available divisions for new applications
        $availableDivisions = Division::where('is_active', true)
            ->with(['supervisor'])
            ->get();

        // Calculate profile completion percentage
        $profileCompletion = $this->calculateProfileCompletion($user, $applications);

        // Get application status steps
        $applicationStatus = $this->getApplicationStatus($applications);

        return Inertia::render('Profile/Index', [
            'user' => $user,
            'applications' => $applications,
            'availableDivisions' => $availableDivisions,
            'profileCompletion' => $profileCompletion,
            'applicationStatus' => $applicationStatus,
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): Response
    {
        return Inertia::render('Profile/Edit', [
            'mustVerifyEmail' => $request->user() instanceof MustVerifyEmail,
            'status' => session('status'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $request->user()->fill($request->validated());

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return Redirect::route('profile.index')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Upload documents for user profile
     */
    public function uploadDocument(Request $request): RedirectResponse
    {
        $request->validate([
            'document_type' => 'required|in:ktp,cv,surat_lamaran,transkrip,foto',
            'document' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $user = $request->user();
        $documentType = $request->document_type;
        $file = $request->file('document');

        // Store the file
        $path = $file->store("documents/{$user->id}/{$documentType}", 'public');

        // Update user profile with document path
        $user->update([
            $documentType . '_path' => $path
        ]);

        return Redirect::route('profile.index')->with('success', 'Dokumen berhasil diunggah!');
    }

    /**
     * Upload multiple documents at once
     */
    public function uploadDocuments(Request $request): RedirectResponse
    {
        $request->validate([
            'documents' => 'required|array',
            'documents.*' => 'file|mimes:pdf,jpg,jpeg,png|max:5120',
        ]);

        $user = $request->user();
        $uploadedFiles = [];

        foreach ($request->file('documents') as $type => $file) {
            if ($file) {
                $path = $file->store("documents/{$user->id}/{$type}", 'public');
                $user->update([
                    $type . '_path' => $path
                ]);
                $uploadedFiles[] = $type;
            }
        }

        $message = count($uploadedFiles) > 0 
            ? 'Dokumen berhasil diunggah: ' . implode(', ', $uploadedFiles)
            : 'Tidak ada dokumen yang diunggah';

        return Redirect::route('profile.index')->with('success', $message);
    }

    /**
     * Create new application from profile
     */
    public function createApplication(Request $request): RedirectResponse
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'motivation_letter' => 'required|string|max:1000',
        ]);

        $user = $request->user();

        // Check if user already has a pending application
        $existingApplication = Application::where('user_id', $user->id)
            ->whereIn('status', ['menunggu', 'proses'])
            ->first();

        if ($existingApplication) {
            return Redirect::route('profile.index')
                ->with('error', 'Anda sudah memiliki lamaran yang sedang diproses.');
        }

        // Create new application
        Application::create([
            'user_id' => $user->id,
            'division_id' => $request->division_id,
            'motivation_letter' => $request->motivation_letter,
            'status' => 'menunggu',
        ]);

        return Redirect::route('profile.index')->with('success', 'Lamaran berhasil dikirim!');
    }

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user, $applications)
    {
        $requiredFields = ['name', 'email', 'phone', 'address'];
        $requiredDocuments = ['ktp_path', 'cv_path', 'foto_path'];
        
        $completedFields = 0;
        $totalFields = count($requiredFields) + count($requiredDocuments) + 1; // +1 for application

        // Check required fields
        foreach ($requiredFields as $field) {
            if (!empty($user->$field)) {
                $completedFields++;
            }
        }

        // Check required documents
        foreach ($requiredDocuments as $document) {
            if (!empty($user->$document)) {
                $completedFields++;
            }
        }

        // Check if has application
        if ($applications->count() > 0) {
            $completedFields++;
        }

        return [
            'percentage' => round(($completedFields / $totalFields) * 100),
            'completed' => $completedFields,
            'total' => $totalFields,
        ];
    }

    /**
     * Get application status information
     */
    private function getApplicationStatus($applications)
    {
        if ($applications->isEmpty()) {
            return [
                'current_step' => 0,
                'steps' => [
                    ['name' => 'Pendaftaran', 'status' => 'pending'],
                    ['name' => 'Verifikasi', 'status' => 'pending'],
                    ['name' => 'Wawancara', 'status' => 'pending'],
                    ['name' => 'Hasil', 'status' => 'pending'],
                ]
            ];
        }

        $latestApplication = $applications->first();
        $status = $latestApplication->status;

        $steps = [
            ['name' => 'Pendaftaran', 'status' => 'completed'],
            ['name' => 'Verifikasi', 'status' => in_array($status, ['proses', 'wawancara', 'diterima']) ? 'completed' : 'pending'],
            ['name' => 'Wawancara', 'status' => in_array($status, ['wawancara', 'diterima']) ? 'completed' : 'pending'],
            ['name' => 'Hasil', 'status' => $status === 'diterima' ? 'completed' : ($status === 'ditolak' ? 'rejected' : 'pending')],
        ];

        $currentStep = 1;
        if ($status === 'proses') $currentStep = 2;
        elseif ($status === 'wawancara') $currentStep = 3;
        elseif (in_array($status, ['diterima', 'ditolak'])) $currentStep = 4;

        return [
            'current_step' => $currentStep,
            'steps' => $steps,
            'status' => $status,
        ];
    }

    /**
     * Upload profile photo
     */
    public function uploadProfilePhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'profile_photo' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048', // 2MB max
        ]);

        $user = $request->user();
        
        // Delete old profile photo if exists
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        // Store the new profile photo
        $path = $request->file('profile_photo')->store('profile-photos', 'public');

        // Update user profile photo path
        $user->update([
            'profile_photo_path' => $path
        ]);

        return Redirect::route('profile.index')->with('status', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}

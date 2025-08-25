<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;
use App\Models\Application;
use App\Models\Division;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form with complete information.
     */
    public function edit(Request $request): Response
    {
        $user = $request->user();
        
        // Get user's applications with division info
        $applications = Application::where('user_id', $user->id)
            ->with('division')
            ->orderBy('created_at', 'desc')
            ->get();
            
        // Calculate profile completion percentage
        $profileCompletion = $this->calculateProfileCompletion($user);
        
        // Get available divisions for new applications
        $divisions = Division::where('is_active', true)
            ->select('id', 'name', 'quota')
            ->get();

        return Inertia::render('Profile/Index', [
            'user' => $user,
            'applications' => $applications,
            'divisions' => $divisions,
            'profileCompletion' => $profileCompletion,
            'mustVerifyEmail' => $user instanceof MustVerifyEmail,
            'status' => session('status'),
            'selectedDivisionId' => $request->get('division_id') ?? session('division_id'),
        ]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validatedData = $request->validated();
        
        // Debug logging
        \Log::info('Profile update request data:', $validatedData);
        \Log::info('User before update:', $user->toArray());
        
        $user->fill($validatedData);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();
        
        // Debug logging after save
        \Log::info('User after update:', $user->fresh()->toArray());

        return Redirect::route('profile.edit')->with('success', 'Profil berhasil diperbarui!');
    }

    /**
     * Upload profile photo
     */
    public function uploadPhoto(Request $request): RedirectResponse
    {
        $request->validate([
            'photo' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $user = $request->user();
        
        // Delete old photo if exists
        if ($user->profile_photo_path) {
            Storage::delete($user->profile_photo_path);
        }

        // Store new photo
        $path = $request->file('photo')->store('profile-photos', 'public');
        
        $user->update([
            'profile_photo_path' => $path
        ]);

        return Redirect::route('profile.edit')->with('success', 'Foto profil berhasil diperbarui!');
    }

    /**
     * Upload documents
     */
    public function uploadDocument(Request $request)
    {
        $request->validate([
            'document_type' => 'required|in:surat_pengantar,cv,motivation_letter,transkrip,ktp,npwp,buku_rekening,pas_foto',
            'document' => 'required|file|mimes:pdf,doc,docx,jpg,jpeg,png|max:5120', // 5MB max
        ]);

        $user = $request->user();
        $documentType = $request->document_type;
        
        // Delete old document if exists
        $oldPath = $user->{$documentType . '_path'};
        if ($oldPath) {
            Storage::delete($oldPath);
        }

        // Store new document
        $path = $request->file('document')->store('documents/' . $documentType, 'public');
        
        $user->update([
            $documentType . '_path' => $path
        ]);

        $documentNames = [
            'surat_pengantar' => 'Surat Pengantar',
            'cv' => 'CV',
            'motivation_letter' => 'Motivation Letter',
            'transkrip' => 'Transkrip Nilai',
            'ktp' => 'KTP',
            'npwp' => 'NPWP',
            'buku_rekening' => 'Buku Rekening Tabungan',
            'pas_foto' => 'Pas Foto'
        ];

        // Return JSON response for AJAX requests
        if ($request->wantsJson() || $request->expectsJson()) {
            return response()->json([
                'success' => true,
                'message' => $documentNames[$documentType] . ' berhasil diunggah!',
                'user' => $user->fresh() // Get updated user data
            ]);
        }

        return Redirect::route('profile.edit')->with('success', $documentNames[$documentType] . ' berhasil diunggah!');
    }

    /**
     * Create new application
     */
    public function createApplication(Request $request)
    {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
            'motivation' => 'required|string|min:100|max:1000',
        ]);

        $user = $request->user();

        // Check if user already has an active application for this division
        $existingApplication = Application::where('user_id', $user->id)
            ->where('division_id', $request->division_id)
            ->whereIn('status', ['menunggu', 'dalam_review', 'wawancara'])
            ->exists();

        if ($existingApplication) {
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda sudah memiliki lamaran aktif untuk divisi ini!'
                ], 422);
            }
            return Redirect::route('profile.edit')->with('error', 'Anda sudah memiliki lamaran aktif untuk divisi ini!');
        }

        try {
            \Log::info('=== CREATING APPLICATION ===');
            \Log::info('User data:', $user->toArray());
            \Log::info('Request data:', $request->all());
            
            $applicationData = [
                'user_id' => $user->id,
                'name' => $user->name ?: 'No Name',
                'email' => $user->email,
                'phone' => $user->phone ?: 'No Phone',
                'address' => $user->address ?: 'No Address',
                'university' => $user->university ?: 'No University',
                'major' => $user->major ?: 'No Major',
                'semester' => $user->semester ?: 1,
                'gpa' => min($user->gpa ?? 0.00, 4.00), // Ensure max 4.00 for decimal(3,2)
                'start_date' => now()->addMonths(1)->format('Y-m-d'), // Default start date
                'end_date' => now()->addMonths(7)->format('Y-m-d'), // Default 6 months internship
                'birth_date' => $user->birth_date,
                'gender' => $user->gender,
                'portfolio_url' => $user->portfolio_url,
                'division_id' => $request->division_id,
                'motivation' => $request->motivation,
                'status' => 'menunggu',
                'cv_path' => $user->cv_path,
                'surat_lamaran_path' => $user->motivation_letter_path,
                'transkrip_path' => $user->transkrip_path,
                'ktp_path' => $user->ktp_path,
            ];
            
            \Log::info('Application data to be created:', $applicationData);
            
            $application = Application::create($applicationData);
            
            \Log::info('Application created successfully:', $application->toArray());

            if ($request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Lamaran berhasil dikirim!'
                ]);
            }

            return Redirect::route('profile.edit')->with('success', 'Lamaran berhasil dikirim!');
        } catch (\Exception $e) {
            \Log::error('Application creation failed: ' . $e->getMessage());
            
            if ($request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.'
                ], 500);
            }
            
            return Redirect::route('profile.edit')->with('error', 'Terjadi kesalahan saat mengirim lamaran. Silakan coba lagi.');
        }
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

    /**
     * Calculate profile completion percentage
     */
    private function calculateProfileCompletion($user): array
    {
        $fields = [
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'profile_photo_path' => $user->profile_photo_path,
            'surat_pengantar_path' => $user->surat_pengantar_path,
            'cv_path' => $user->cv_path,
            'motivation_letter_path' => $user->motivation_letter_path,
            'transkrip_path' => $user->transkrip_path,
            'ktp_path' => $user->ktp_path,
            'buku_rekening_path' => $user->buku_rekening_path,
            'pas_foto_path' => $user->pas_foto_path,
            // npwp_path is optional, so not included in completion calculation
        ];

        $completed = collect($fields)->filter()->count();
        $total = count($fields);
        $percentage = round(($completed / $total) * 100);

        return [
            'percentage' => $percentage,
            'completed' => $completed,
            'total' => $total,
            'missing' => array_keys(array_filter($fields, fn($value) => empty($value)))
        ];
    }
}

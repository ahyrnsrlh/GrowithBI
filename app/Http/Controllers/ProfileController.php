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
use App\Models\Logbook;
use App\Models\Report;

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
            
        // Check if user has a pending or accepted application
        $activeApplication = $applications->whereIn('status', ['menunggu', 'dalam_review', 'wawancara', 'diterima'])->first();
        $canCreateNewApplication = !$activeApplication;
            
        // Calculate profile completion percentage
        $profileCompletion = $this->calculateProfileCompletion($user);
        
        // Get available divisions for new applications
        $divisions = Division::where('is_active', true)
            ->select('id', 'name', 'max_interns')
            ->get()
            ->map(function ($division) {
                return [
                    'id' => $division->id,
                    'name' => $division->name,
                    'quota' => $division->max_interns
                ];
            });
            
        // Get logbook data for accepted participants
        $logbooks = collect();
        $logbookStats = [
            'total_logbooks' => 0,
            'pending_logbooks' => 0,
            'approved_logbooks' => 0,
            'revision_logbooks' => 0,
            'total_hours' => 0,
            'average_hours' => 0
        ];
        
        // Get reports data for accepted participants
        $reports = collect();
        $reportStats = [
            'total_reports' => 0,
            'pending_reports' => 0,
            'approved_reports' => 0,
            'revision_reports' => 0
        ];
        
        $acceptedApplication = $applications->where('status', 'diterima')->first();
        if ($acceptedApplication) {
            $logbooks = Logbook::where('user_id', $user->id)
                ->with(['division', 'comments.user'])
                ->orderBy('date', 'desc')
                ->limit(5)
                ->get();
                
            $allLogbooks = Logbook::where('user_id', $user->id)->get();
            $logbookStats = [
                'total_logbooks' => $allLogbooks->count(),
                'pending_logbooks' => $allLogbooks->where('status', 'submitted')->count(),
                'approved_logbooks' => $allLogbooks->where('status', 'approved')->count(),
                'revision_logbooks' => $allLogbooks->where('status', 'revision')->count(),
                'total_hours' => $allLogbooks->sum('duration'),
                'average_hours' => $allLogbooks->count() > 0 ? round($allLogbooks->avg('duration'), 1) : 0
            ];
            
            $reports = Report::where('user_id', $user->id)
                ->orderBy('created_at', 'desc')
                ->limit(5)
                ->get();
                
            $allReports = Report::where('user_id', $user->id)->get();
            $reportStats = [
                'total_reports' => $allReports->count(),
                'pending_reports' => $allReports->where('status', 'submitted')->count(),
                'approved_reports' => $allReports->where('status', 'approved')->count(),
                'revision_reports' => $allReports->where('status', 'revision')->count()
            ];
        }

        return Inertia::render('Profile/Index', [
            'user' => $user,
            'applications' => $applications,
            'activeApplication' => $activeApplication,
            'canCreateNewApplication' => $canCreateNewApplication,
            'divisions' => $divisions,
            'logbooks' => $logbooks,
            'logbookStats' => $logbookStats,
            'reports' => $reports,
            'reportStats' => $reportStats,
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
        $user = $request->user();
        
        $request->validate([
            'division_id' => ['required', 'exists:divisions,id', new \App\Rules\UniqueActiveApplication(null, $user->id)],
            'motivation' => 'required|string|min:100|max:1000',
        ]);

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
     * Cancel user application - delete the application record
     */
    public function cancelApplication(Request $request, $id)
    {
        $application = Application::findOrFail($id);
        
        // Ensure user can only cancel their own application
        if ($application->user_id !== Auth::id()) {
            abort(403, 'Unauthorized');
        }
        
        // Only allow canceling pending applications
        if (!in_array($application->status, ['menunggu', 'dalam_review'])) {
            return back()->withErrors(['error' => 'Aplikasi tidak dapat dibatalkan karena sudah diproses lebih lanjut.']);
        }
        
        // Delete uploaded files if they exist
        $filePaths = [
            $application->cv_path,
            $application->surat_lamaran_path,
            $application->transkrip_path,
            $application->ktp_path
        ];
        
        foreach ($filePaths as $filePath) {
            if ($filePath && Storage::disk('public')->exists($filePath)) {
                Storage::disk('public')->delete($filePath);
            }
        }
        
        // Delete the application record
        $application->delete();
        
        return redirect()->route('profile.edit')->with('success', 'Pendaftaran berhasil dibatalkan dan dihapus dari riwayat.');
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

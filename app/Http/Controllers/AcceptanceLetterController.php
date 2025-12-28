<?php

namespace App\Http\Controllers;

use App\Events\AcceptanceLetterUploaded;
use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;

class AcceptanceLetterController extends Controller
{
    /**
     * Check if request is from Inertia
     */
    private function isInertiaRequest(Request $request): bool
    {
        return $request->header('X-Inertia') === 'true';
    }

    /**
     * Check if request expects JSON (non-Inertia AJAX)
     */
    private function expectsJson(Request $request): bool
    {
        return !$this->isInertiaRequest($request) && ($request->wantsJson() || $request->ajax());
    }

    /**
     * Upload acceptance letter by mentor/admin
     * 
     * This method handles both Inertia and AJAX requests.
     */
    public function upload(Request $request, Application $application): JsonResponse|RedirectResponse
    {
        // Validate user has permission to upload (admin or mentor)
        $user = Auth::user();
        if (!in_array($user->role, ['admin', 'mentor'])) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized. Only admin and mentors can upload acceptance letters.'
                ], 403);
            }
            return redirect()->back()->with('error', 'Unauthorized. Only admin and mentors can upload acceptance letters.');
        }

        // Validate the application is accepted or letter_ready
        if (!in_array($application->status, ['accepted', 'letter_ready'])) {
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Can only upload acceptance letter for accepted applications.'
                ], 400);
            }
            return redirect()->back()->with('error', 'Can only upload acceptance letter for accepted applications.');
        }

        $request->validate([
            'acceptance_letter' => 'required|file|mimes:pdf|max:5120', // 5MB max
        ]);

        try {
            // Delete old file if exists
            if ($application->acceptance_letter_path && Storage::disk('public')->exists($application->acceptance_letter_path)) {
                Storage::disk('public')->delete($application->acceptance_letter_path);
            }

            // Store new file
            $file = $request->file('acceptance_letter');
            $filename = 'acceptance_letter_' . $application->id . '_' . time() . '.pdf';
            $path = $file->storeAs('acceptance_letters', $filename, 'public');

            // Update application
            $application->update([
                'acceptance_letter_path' => $path,
                'acceptance_letter_uploaded_at' => now(),
                'uploaded_by' => $user->id
            ]);

            // Dispatch event to notify user and update status
            AcceptanceLetterUploaded::dispatch($application->fresh(['user', 'division']), $user);

            Log::info('Acceptance letter uploaded', [
                'application_id' => $application->id,
                'uploaded_by' => $user->id,
                'path' => $path,
            ]);

            // Return appropriate response based on request type
            // For Inertia requests, always return redirect with flash message
            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => true,
                    'message' => 'Surat penerimaan berhasil diupload.',
                    'data' => [
                        'path' => $path,
                        'uploaded_at' => now()->format('d/m/Y H:i'),
                    ]
                ]);
            }

            // For Inertia and normal requests - redirect back with success message
            return redirect()->back()->with('success', 'Surat penerimaan berhasil diupload dan notifikasi telah dikirim ke peserta.');

        } catch (\Exception $e) {
            Log::error('Failed to upload acceptance letter', [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);

            if ($this->expectsJson($request)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Gagal mengupload surat penerimaan: ' . $e->getMessage()
                ], 500);
            }

            return redirect()->back()->with('error', 'Gagal mengupload surat penerimaan: ' . $e->getMessage());
        }
    }

    /**
     * Download acceptance letter by participant
     */
    public function download(Application $application)
    {
        $user = Auth::user();
        
        // Check if user is the owner of the application or admin/mentor
        if ($application->user_id !== $user->id && !in_array($user->role, ['admin', 'mentor'])) {
            abort(403, 'Unauthorized to download this acceptance letter.');
        }

        // Check if application is accepted or letter_ready
        if (!in_array($application->status, ['accepted', 'letter_ready'])) {
            abort(404, 'Acceptance letter not available for this application.');
        }

        // Check if acceptance letter exists
        if (!$application->acceptance_letter_path || !Storage::disk('public')->exists($application->acceptance_letter_path)) {
            abort(404, 'Acceptance letter file not found.');
        }

        // Generate download filename
        $filename = 'Surat_Penerimaan_' . $application->user->name . '_' . $application->division->name . '.pdf';

        // Return file download
        return Storage::disk('public')->download($application->acceptance_letter_path, $filename);
    }

    /**
     * Check if acceptance letter exists for application
     */
    public function check(Application $application): JsonResponse
    {
        $user = Auth::user();
        
        // Check if user is the owner of the application or admin/mentor
        if ($application->user_id !== $user->id && !in_array($user->role, ['admin', 'mentor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        $hasLetter = $application->acceptance_letter_path && 
                    Storage::disk('public')->exists($application->acceptance_letter_path);

        return response()->json([
            'success' => true,
            'has_letter' => $hasLetter,
            'can_download' => $application->canDownloadAcceptanceLetter(),
            'status' => $application->status,
            'status_info' => $application->status_info,
            'uploaded_at' => $hasLetter ? $application->acceptance_letter_uploaded_at?->format('d/m/Y H:i') : null,
            'uploaded_by' => $hasLetter ? $application->letterUploader?->name : null
        ]);
    }
}

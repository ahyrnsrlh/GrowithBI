<?php

namespace App\Http\Controllers;

use App\Models\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Http\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class AcceptanceLetterController extends Controller
{
    /**
     * Upload acceptance letter by mentor/admin
     */
    public function upload(Request $request, Application $application): JsonResponse
    {
        // Validate user has permission to upload (admin or mentor)
        $user = Auth::user();
        if (!$user->hasRole(['admin', 'mentor'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized. Only admin and mentors can upload acceptance letters.'
            ], 403);
        }

        // Validate the application is accepted
        if ($application->status !== 'diterima') {
            return response()->json([
                'success' => false,
                'message' => 'Can only upload acceptance letter for accepted applications.'
            ], 400);
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

        return back()->with('success', 'Surat penerimaan berhasil diupload.');        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengupload surat penerimaan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Download acceptance letter by participant
     */
    public function download(Application $application)
    {
        $user = Auth::user();
        
        // Check if user is the owner of the application or admin/mentor
        if ($application->user_id !== $user->id && !$user->hasRole(['admin', 'mentor'])) {
            abort(403, 'Unauthorized to download this acceptance letter.');
        }

        // Check if application is accepted
        if ($application->status !== 'diterima') {
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
        if ($application->user_id !== $user->id && !$user->hasRole(['admin', 'mentor'])) {
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
            'uploaded_at' => $hasLetter ? $application->acceptance_letter_uploaded_at?->format('d/m/Y H:i') : null,
            'uploaded_by' => $hasLetter ? $application->letterUploader?->name : null
        ]);
    }
}

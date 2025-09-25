<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function checkExisting(Request $request, $divisionId)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json([
                'canApply' => false,
                'message' => 'User not authenticated'
            ]);
        }
        
        // Check if user already has an active application
        $hasApplication = Application::where('user_id', $user->id)
            ->where('division_id', $divisionId)
            ->whereIn('status', ['menunggu', 'dalam_review', 'wawancara'])
            ->exists();

        if ($hasApplication) {
            return response()->json([
                'canApply' => false,
                'message' => 'Anda sudah memiliki aplikasi yang sedang diproses untuk divisi ini.'
            ]);
        }
            
        // Check profile completeness
        $missingPersonalData = [];
        $missingDocuments = [];

        // Required personal data fields
        $requiredPersonalData = [
            'phone' => 'Nomor Telepon',
            'address' => 'Alamat',
            'university' => 'Universitas',
            'major' => 'Jurusan',
            'semester' => 'Semester',
            'gpa' => 'IPK',
            'birth_date' => 'Tanggal Lahir',
            'gender' => 'Jenis Kelamin'
        ];

        foreach ($requiredPersonalData as $field => $label) {
            if (empty($user->$field)) {
                $missingPersonalData[] = $label;
            }
        }

        // Required document fields
        $requiredDocuments = [
            'ktp_path' => 'KTP',
            'cv_path' => 'CV',
            'transkrip_path' => 'Transkrip Nilai',
            'surat_pengantar_path' => 'Surat Pengantar',
            'motivation_letter_path' => 'Motivation Letter',
            'pas_foto_path' => 'Pas Foto'
        ];

        foreach ($requiredDocuments as $field => $label) {
            if (empty($user->$field)) {
                $missingDocuments[] = $label;
            }
        }

        $canApply = empty($missingPersonalData) && empty($missingDocuments);

        return response()->json([
            'canApply' => $canApply,
            'missingPersonalData' => $missingPersonalData,
            'missingDocuments' => $missingDocuments,
            'message' => $canApply ? 'Profile lengkap, dapat mendaftar' : 'Profile belum lengkap'
        ]);
    }

    public function getStatus(Request $request, $applicationId)
    {
        $user = $request->user();
        
        if (!$user) {
            return response()->json(['error' => 'Unauthorized'], 401);
        }
        
        $application = Application::where('id', $applicationId)
            ->where('user_id', $user->id)
            ->first();
            
        if (!$application) {
            return response()->json([
                'error' => 'Application not found'
            ], 404);
        }
        
        return response()->json([
            'id' => $application->id,
            'status' => $application->status,
            'updated_at' => $application->updated_at,
            'created_at' => $application->created_at
        ]);
    }
}

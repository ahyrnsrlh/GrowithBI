<?php

namespace App\Http\Controllers;

use App\Models\Division;
use App\Models\Application;
use Illuminate\Http\Request;
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

    public function divisionDetail(Division $division)
    {
        $division->load(['supervisor']);
        
        // Get accepted applications count
        $acceptedCount = Application::where('division_id', $division->id)
            ->where('status', 'diterima')
            ->count();

        // Define division-specific data
        $divisionSpecificData = $this->getDivisionSpecificData($division->name);

        $divisionData = [
            'id' => $division->id,
            'name' => $division->name,
            'description' => $division->description,
            'requirements' => $division->requirements,
            'quota' => $division->quota,
            'current_interns' => $acceptedCount,
            'available_slots' => $division->quota - $acceptedCount,
            'supervisor' => $division->supervisor ? $division->supervisor->name : 'Belum ditentukan',
            'supervisor_email' => $division->supervisor ? $division->supervisor->email : null,
            'job_type' => 'Magang',
            'duration' => '6 bulan',
            'location' => 'Jakarta',
            'employment_type' => 'Onsite',
            'application_deadline' => '19 Agustus 2025',
            'selection_announcement' => '14 September 2025',
        ];

        // Merge with division-specific data
        $divisionData = array_merge($divisionData, $divisionSpecificData);

        return Inertia::render('Public/DivisionDetail', [
            'division' => $divisionData,
            'auth' => [
                'user' => auth()->user()
            ]
        ]);
    }

    private function getDivisionSpecificData($divisionName)
    {
        $data = [
            'Business Intelligence & Analytics' => [
                'required_documents' => [
                    'CV',
                    'KTP', 
                    'Ijazah/Surat Keterangan Lulus (bagi Fresh Graduate)',
                    'Transkrip Nilai',
                    'Portfolio Data Analysis'
                ],
                'job_description' => [
                    '1. Melakukan analisis data bisnis untuk mendukung pengambilan keputusan strategis',
                    '2. Membuat dashboard dan visualisasi data menggunakan tools BI modern', 
                    '3. Mengembangkan model prediktif untuk forecasting bisnis',
                    '4. Melakukan data mining dan pattern recognition'
                ],
                'required_education' => [
                    'Jenjang pendidikan: S1, D3, D4',
                    'Jurusan: Statistika, Matematika, Ilmu Komputer, Sistem Informasi',
                    'IPK minimal: 3.0'
                ]
            ],
            'Software Development' => [
                'required_documents' => [
                    'CV',
                    'KTP', 
                    'Ijazah/Surat Keterangan Lulus (bagi Fresh Graduate)',
                    'Transkrip Nilai',
                    'Portfolio Programming Projects'
                ],
                'job_description' => [
                    '1. Mengembangkan aplikasi web dan mobile menggunakan teknologi modern',
                    '2. Melakukan testing dan debugging aplikasi', 
                    '3. Berkolaborasi dalam tim development menggunakan metodologi Agile',
                    '4. Membuat dokumentasi teknis dan user manual'
                ],
                'required_education' => [
                    'Jenjang pendidikan: S1, D3, D4',
                    'Jurusan: Teknik Informatika, Ilmu Komputer, Sistem Informasi',
                    'IPK minimal: 3.0'
                ]
            ],
            'Data Science & Machine Learning' => [
                'required_documents' => [
                    'CV',
                    'KTP', 
                    'Ijazah/Surat Keterangan Lulus (bagi Fresh Graduate)',
                    'Transkrip Nilai',
                    'Portfolio Machine Learning Projects'
                ],
                'job_description' => [
                    '1. Mengembangkan model machine learning untuk prediksi bisnis',
                    '2. Melakukan preprocessing dan feature engineering pada big data', 
                    '3. Implementasi algoritma AI untuk otomatisasi proses',
                    '4. Evaluasi dan optimasi performa model ML'
                ],
                'required_education' => [
                    'Jenjang pendidikan: S1, D3, D4',
                    'Jurusan: Data Science, Statistika, Matematika, Ilmu Komputer',
                    'IPK minimal: 3.2'
                ]
            ]
        ];

        // Default data if division not found
        $defaultData = [
            'required_documents' => [
                'CV',
                'KTP', 
                'Ijazah/Surat Keterangan Lulus (bagi Fresh Graduate)',
                'Transkrip Nilai'
            ],
            'job_description' => [
                '1. Melakukan tugas-tugas sesuai dengan bidang keahlian',
                '2. Belajar dan mengembangkan keterampilan profesional', 
                '3. Berpartisipasi dalam proyek-proyek perusahaan'
            ],
            'required_education' => [
                'Jenjang pendidikan: S1, D3, D4',
                'Jurusan: Sesuai dengan bidang divisi',
                'IPK minimal: 2.5'
            ]
        ];

        return $data[$divisionName] ?? $defaultData;
    }

    public function applicationForm(Request $request)
    {
        $divisions = Division::where('is_active', true)
            ->with(['supervisor'])
            ->get();

        $selectedDivisionId = $request->query('division_id');

        return Inertia::render('Public/ApplicationForm', [
            'divisions' => $divisions,
            'selectedDivisionId' => $selectedDivisionId
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

    public function checkStatus()
    {
        return Inertia::render('Public/CheckStatus');
    }

    public function searchStatus(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'phone' => 'required|string',
        ]);

        $application = Application::where('email', $request->email)
            ->where('phone', $request->phone)
            ->with(['division'])
            ->first();

        if (!$application) {
            return back()->withErrors([
                'search' => 'Data aplikasi tidak ditemukan. Pastikan email dan nomor telepon yang Anda masukkan benar.'
            ]);
        }

        $statusData = [
            'name' => $application->name,
            'email' => $application->email,
            'division' => $application->division->name,
            'status' => $application->status,
            'created_at' => $application->created_at->format('d M Y H:i'),
            'updated_at' => $application->updated_at->format('d M Y H:i'),
        ];

        return back()->with('application', $statusData);
    }

    public function quickRegister(Request $request)
    {
        $user = auth()->user();
        
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus login terlebih dahulu'
            ], 401);
        }

        // Check if user already has an application
        $existingApplication = Application::where('email', $user->email)->first();
        if ($existingApplication) {
            return response()->json([
                'success' => false,
                'message' => 'Anda sudah pernah mendaftar sebelumnya'
            ], 400);
        }

        $request->validate([
            'division_id' => 'required|exists:divisions,id'
        ]);

        // Define required documents
        $requiredDocuments = [
            'ktp_path' => 'KTP',
            'cv_path' => 'CV',
            'surat_lamaran_path' => 'Surat Lamaran',
            'transkrip_path' => 'Transkrip Nilai',
            'foto_path' => 'Foto'
        ];

        // Check missing documents
        $missingDocuments = [];
        foreach ($requiredDocuments as $field => $name) {
            if (empty($user->$field)) {
                $missingDocuments[] = $name;
            }
        }

        // If there are missing documents, return error
        if (!empty($missingDocuments)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Anda belum melengkapi data dokumen: ' . implode(', ', $missingDocuments)
            ], 400);
        }

        // Check if user profile is complete
        if (empty($user->phone) || empty($user->address)) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal! Silakan lengkapi profil Anda terlebih dahulu (nomor telepon dan alamat)'
            ], 400);
        }

        // Create application
        Application::create([
            'name' => $user->name,
            'email' => $user->email,
            'phone' => $user->phone,
            'address' => $user->address,
            'division_id' => $request->division_id,
            'cv_url' => $user->cv_path,
            'cover_letter_url' => $user->surat_lamaran_path,
            'portfolio_url' => null, // Portfolio is optional
            'motivation' => 'Pendaftaran langsung melalui sistem',
            'status' => 'menunggu',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Berhasil! Aplikasi magang Anda telah dikirim. Kami akan menghubungi Anda segera.'
        ]);
    }
}

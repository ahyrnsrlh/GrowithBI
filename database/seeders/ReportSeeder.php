<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Report;
use App\Models\User;
use App\Models\Application;

class ReportSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get first user (non-admin)
        $user = User::where('role', '!=', 'admin')->first();
        
        if (!$user) {
            $this->command->error('No non-admin user found. Please run UserSeeder first.');
            return;
        }

        // Get user's application (if any)
        $application = Application::where('user_id', $user->id)->first();

        // Create sample reports
        $reports = [
            [
                'user_id' => $user->id,
                'application_id' => $application ? $application->id : null,
                'title' => 'Laporan Magang Minggu 1-4',
                'description' => 'Laporan kegiatan magang untuk periode minggu 1-4, berisi ringkasan aktivitas pembelajaran dan pengalaman di divisi.',
                'file_path' => 'reports/sample-report-1.pdf',
                'file_name' => 'laporan-minggu-1-4.pdf',
                'file_size' => '2048000',
                'file_type' => 'application/pdf',
                'status' => 'submitted',
                'created_at' => now()->subDays(7),
                'updated_at' => now()->subDays(7),
            ],
            [
                'user_id' => $user->id,
                'application_id' => $application ? $application->id : null,
                'title' => 'Laporan Magang Minggu 5-8',
                'description' => 'Laporan kegiatan magang untuk periode minggu 5-8, berisi evaluasi progress dan pencapaian target yang telah ditetapkan.',
                'file_path' => 'reports/sample-report-2.pdf',
                'file_name' => 'laporan-minggu-5-8.pdf',
                'file_size' => '3072000',
                'file_type' => 'application/pdf',
                'status' => 'approved',
                'feedback' => 'Laporan sudah baik, lengkap dan sesuai dengan format yang diminta.',
                'reviewed_by' => User::where('role', 'admin')->first()->id ?? null,
                'reviewed_at' => now()->subDays(2),
                'created_at' => now()->subDays(5),
                'updated_at' => now()->subDays(2),
            ],
            [
                'user_id' => $user->id,
                'application_id' => $application ? $application->id : null,
                'title' => 'Laporan Akhir Magang',
                'description' => 'Laporan akhir kegiatan magang yang berisi seluruh rangkuman aktivitas, pembelajaran, dan evaluasi selama periode magang berlangsung.',
                'file_path' => 'reports/sample-report-3.pdf',
                'file_name' => 'laporan-akhir-magang.pdf',
                'file_size' => '5120000',
                'file_type' => 'application/pdf',
                'status' => 'revision',
                'feedback' => 'Laporan perlu dilengkapi dengan analisis yang lebih mendalam pada bagian evaluasi.',
                'reviewed_by' => User::where('role', 'admin')->first()->id ?? null,
                'reviewed_at' => now()->subDays(1),
                'created_at' => now()->subDays(3),
                'updated_at' => now()->subDays(1),
            ],
        ];

        foreach ($reports as $reportData) {
            Report::create($reportData);
        }

        $this->command->info('Reports seeded successfully!');
    }
}

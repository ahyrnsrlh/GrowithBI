<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use App\Models\Application;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin
        $admin = User::create([
            'name' => 'Admin GrowithBI',
            'email' => 'admin@growithbi.com',
            'password' => Hash::make('password'),
            'role' => 'admin',
            'phone' => '081234567890',
            'is_active' => true,
        ]);

        // Create Pembimbing users
        $pembimbings = [
            [
                'name' => 'Dr. Andi Wijaya',
                'email' => 'andi.wijaya@growithbi.com',
                'password' => Hash::make('password'),
                'role' => 'pembimbing',
                'phone' => '081234567891',
            ],
            [
                'name' => 'Sarah Novita, M.Kom',
                'email' => 'sarah.novita@growithbi.com',
                'password' => Hash::make('password'),
                'role' => 'pembimbing',
                'phone' => '081234567892',
            ],
            [
                'name' => 'Dimas Yoga, S.E',
                'email' => 'dimas.yoga@growithbi.com',
                'password' => Hash::make('password'),
                'role' => 'pembimbing',
                'phone' => '081234567893',
            ],
            [
                'name' => 'Lia Kurniawan, M.T',
                'email' => 'lia.kurniawan@growithbi.com',
                'password' => Hash::make('password'),
                'role' => 'pembimbing',
                'phone' => '081234567894',
            ],
        ];

        foreach ($pembimbings as $pembimbing) {
            User::create($pembimbing);
        }

        // Create Divisions
        $divisions = [
            [
                'name' => 'IT Development',
                'description' => 'Divisi pengembangan aplikasi web dan mobile. Peserta akan belajar teknologi seperti Laravel, Vue.js, React, dan mobile development.',
                'quota' => 10,
                'supervisor_id' => 2, // Dr. Andi Wijaya
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'is_active' => true,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Divisi desain antarmuka dan pengalaman pengguna. Peserta akan belajar Figma, Adobe XD, dan prinsip-prinsip design thinking.',
                'quota' => 8,
                'supervisor_id' => 3, // Sarah Novita
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'is_active' => true,
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Divisi pemasaran digital dan strategi konten. Peserta akan belajar SEO, SEM, social media marketing, dan content creation.',
                'quota' => 6,
                'supervisor_id' => 4, // Dimas Yoga
                'start_date' => '2025-07-15',
                'end_date' => '2025-10-15',
                'is_active' => true,
            ],
            [
                'name' => 'Data Analytics',
                'description' => 'Divisi analisis data dan business intelligence. Peserta akan belajar Python, SQL, Power BI, dan data visualization.',
                'quota' => 5,
                'supervisor_id' => 5, // Lia Kurniawan
                'start_date' => '2025-08-01',
                'end_date' => '2025-11-30',
                'is_active' => true,
            ],
            [
                'name' => 'Content Writing',
                'description' => 'Divisi penulisan konten dan copywriting. Peserta akan belajar SEO writing, technical writing, dan content strategy.',
                'quota' => 4,
                'supervisor_id' => 3, // Sarah Novita
                'start_date' => '2025-07-01',
                'end_date' => '2025-09-30',
                'is_active' => true,
            ],
            [
                'name' => 'Quality Assurance',
                'description' => 'Divisi pengujian perangkat lunak dan quality control. Peserta akan belajar manual testing, automation testing, dan bug tracking.',
                'quota' => 6,
                'supervisor_id' => 2, // Dr. Andi Wijaya
                'start_date' => '2025-08-01',
                'end_date' => '2025-11-30',
                'is_active' => true,
            ],
        ];

        foreach ($divisions as $division) {
            Division::create($division);
        }

        // Create Peserta users dan Applications
        $pesertas = [
            [
                'name' => 'Ahmad Fadli',
                'email' => 'ahmad.fadli@email.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'phone' => '081234560001',
                'address' => 'Jl. Sudirman No. 123, Jakarta',
            ],
            [
                'name' => 'Siti Rahayu',
                'email' => 'siti.rahayu@email.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'phone' => '081234560002',
                'address' => 'Jl. Thamrin No. 456, Jakarta',
            ],
            [
                'name' => 'Budi Santoso',
                'email' => 'budi.santoso@email.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'phone' => '081234560003',
                'address' => 'Jl. Gatot Subroto No. 789, Jakarta',
            ],
            [
                'name' => 'Maya Putri',
                'email' => 'maya.putri@email.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'phone' => '081234560004',
                'address' => 'Jl. Kuningan No. 321, Jakarta',
            ],
            [
                'name' => 'Riko Pratama',
                'email' => 'riko.pratama@email.com',
                'password' => Hash::make('password'),
                'role' => 'peserta',
                'phone' => '081234560005',
                'address' => 'Jl. Senayan No. 654, Jakarta',
            ],
        ];

        foreach ($pesertas as $peserta) {
            User::create($peserta);
        }

        // Create Applications
        $applications = [
            [
                'user_id' => 6, // Ahmad Fadli
                'division_id' => 1, // IT Development
                'status' => 'menunggu',
                'motivation_letter' => 'Saya sangat tertarik dengan pengembangan aplikasi web dan ingin mengembangkan skill programming saya.',
                'created_at' => now()->subDays(2),
            ],
            [
                'user_id' => 7, // Siti Rahayu
                'division_id' => 2, // UI/UX Design
                'status' => 'diterima',
                'motivation_letter' => 'Desain UI/UX adalah passion saya dan saya ingin belajar lebih dalam tentang user experience.',
                'reviewed_at' => now()->subDay(),
                'reviewed_by' => 1, // Admin
                'admin_notes' => 'Portfolio yang sangat menarik dan sesuai dengan kebutuhan divisi.',
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => 8, // Budi Santoso
                'division_id' => 3, // Digital Marketing
                'status' => 'menunggu',
                'motivation_letter' => 'Saya ingin belajar strategi pemasaran digital dan mengembangkan skill di bidang marketing.',
                'created_at' => now()->subDays(3),
            ],
            [
                'user_id' => 9, // Maya Putri
                'division_id' => 4, // Data Analytics
                'status' => 'diterima',
                'motivation_letter' => 'Data analytics adalah bidang yang sangat menarik bagi saya dan saya ingin menguasai tools analisis data.',
                'reviewed_at' => now()->subDays(2),
                'reviewed_by' => 1, // Admin
                'admin_notes' => 'Background matematika yang kuat, sangat cocok untuk divisi ini.',
                'created_at' => now()->subDays(4),
            ],
            [
                'user_id' => 10, // Riko Pratama
                'division_id' => 5, // Content Writing
                'status' => 'ditolak',
                'motivation_letter' => 'Saya suka menulis dan ingin mengembangkan skill content writing untuk karir saya.',
                'reviewed_at' => now()->subDays(2),
                'reviewed_by' => 1, // Admin
                'admin_notes' => 'Portfolio writing masih perlu ditingkatkan, silakan apply lagi di periode berikutnya.',
                'created_at' => now()->subDays(4),
            ],
        ];

        foreach ($applications as $application) {
            Application::create($application);
        }
    }
}

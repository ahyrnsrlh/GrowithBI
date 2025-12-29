<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Division;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DashboardDummySeeder extends Seeder
{
    /**
     * Seed dashboard with comprehensive dummy data.
     */
    public function run(): void
    {
        $this->command->info('🚀 Seeding Dashboard Dummy Data...');

        // Create Admin if not exists
        $admin = User::firstOrCreate(
            ['email' => 'admin@growithbi.com'],
            [
                'name' => 'Admin GrowithBI',
                'password' => Hash::make('password123'),
                'role' => 'admin',
                'phone' => '081234567890',
                'is_active' => true,
                'email_verified_at' => now(),
            ]
        );

        $this->command->info('✅ Admin user ready');

        // Create Divisions with quota
        $divisions = $this->createDivisions();
        $this->command->info('✅ ' . count($divisions) . ' divisions created');

        // Create Pembimbing (Supervisors)
        $supervisors = $this->createSupervisors();
        $this->command->info('✅ ' . count($supervisors) . ' supervisors created');

        // Create Peserta (Interns/Applicants)
        $peserta = $this->createPeserta();
        $this->command->info('✅ ' . count($peserta) . ' peserta created');

        // Create Applications with various statuses
        $applications = $this->createApplications($peserta, $divisions);
        $this->command->info('✅ ' . count($applications) . ' applications created');

        $this->command->info('');
        $this->command->info('📊 Dashboard Dummy Data Summary:');
        $this->command->info('   - Total Pendaftar: ' . count($applications));
        $this->command->info('   - Menunggu: ' . collect($applications)->where('status', 'menunggu')->count());
        $this->command->info('   - Diterima: ' . collect($applications)->where('status', 'diterima')->count());
        $this->command->info('   - Ditolak: ' . collect($applications)->where('status', 'ditolak')->count());
        $this->command->info('');
        $this->command->info('🔐 Login Credentials:');
        $this->command->info('   Admin: admin@growithbi.com / password123');
    }

    private function createDivisions(): array
    {
        $divisionsData = [
            [
                'name' => 'Business Intelligence Banking',
                'description' => 'Program magang yang berfokus pada analisis data perbankan dan dashboard BI.',
                'quota' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Data Analytics & Research',
                'description' => 'Program magang untuk analisis data ekonomi dan keuangan.',
                'quota' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Financial Technology',
                'description' => 'Program magang teknologi finansial dan digital banking.',
                'quota' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Program magang desain interface dan pengalaman pengguna.',
                'quota' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Digital Marketing',
                'description' => 'Program magang pemasaran digital dan social media.',
                'quota' => 6,
                'is_active' => true,
            ],
        ];

        $created = [];
        foreach ($divisionsData as $data) {
            $created[] = Division::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }

        return $created;
    }

    private function createSupervisors(): array
    {
        $supervisorsData = [
            ['name' => 'Dr. Sarah Wijaya', 'email' => 'sarah.wijaya@growithbi.com'],
            ['name' => 'Ir. Budi Santoso, M.T.', 'email' => 'budi.santoso@growithbi.com'],
            ['name' => 'Prof. Dewi Kusuma', 'email' => 'dewi.kusuma@growithbi.com'],
            ['name' => 'Drs. Agus Prasetyo', 'email' => 'agus.prasetyo@growithbi.com'],
        ];

        $created = [];
        foreach ($supervisorsData as $data) {
            $created[] = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'pembimbing',
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        return $created;
    }

    private function createPeserta(): array
    {
        $pesertaData = [
            ['name' => 'Ahmad Rizki Pratama', 'email' => 'ahmad.rizki@mail.com', 'university' => 'Universitas Indonesia'],
            ['name' => 'Sari Dewi Lestari', 'email' => 'sari.dewi@mail.com', 'university' => 'Institut Teknologi Bandung'],
            ['name' => 'Budi Santoso Putra', 'email' => 'budi.santoso@mail.com', 'university' => 'Universitas Gadjah Mada'],
            ['name' => 'Maya Putri Anggraini', 'email' => 'maya.putri@mail.com', 'university' => 'Universitas Airlangga'],
            ['name' => 'Eko Prasetyo Wibowo', 'email' => 'eko.prasetyo@mail.com', 'university' => 'Institut Teknologi Sepuluh Nopember'],
            ['name' => 'Dian Permata Sari', 'email' => 'dian.permata@mail.com', 'university' => 'Universitas Brawijaya'],
            ['name' => 'Rudi Hermawan', 'email' => 'rudi.hermawan@mail.com', 'university' => 'Universitas Padjadjaran'],
            ['name' => 'Putri Ayu Rahmawati', 'email' => 'putri.ayu@mail.com', 'university' => 'Universitas Diponegoro'],
            ['name' => 'Andi Wijaya Kusuma', 'email' => 'andi.wijaya@mail.com', 'university' => 'Universitas Indonesia'],
            ['name' => 'Rina Susanti', 'email' => 'rina.susanti@mail.com', 'university' => 'Institut Teknologi Bandung'],
            ['name' => 'Bambang Suryanto', 'email' => 'bambang.suryanto@mail.com', 'university' => 'Universitas Hasanuddin'],
            ['name' => 'Lina Marlina', 'email' => 'lina.marlina@mail.com', 'university' => 'Universitas Udayana'],
            ['name' => 'Fikri Ramadhan', 'email' => 'fikri.ramadhan@mail.com', 'university' => 'Telkom University'],
            ['name' => 'Nadia Safitri', 'email' => 'nadia.safitri@mail.com', 'university' => 'Binus University'],
            ['name' => 'Yoga Pratama', 'email' => 'yoga.pratama@mail.com', 'university' => 'Universitas Multimedia Nusantara'],
            ['name' => 'Aulia Rahma', 'email' => 'aulia.rahma@mail.com', 'university' => 'Universitas Trisakti'],
            ['name' => 'Rizal Fahmi', 'email' => 'rizal.fahmi@mail.com', 'university' => 'Universitas Atmajaya'],
            ['name' => 'Indah Permatasari', 'email' => 'indah.permata@mail.com', 'university' => 'Universitas Pelita Harapan'],
            ['name' => 'Dimas Ardiansyah', 'email' => 'dimas.ardiansyah@mail.com', 'university' => 'Institut Pertanian Bogor'],
            ['name' => 'Sinta Dewi', 'email' => 'sinta.dewi@mail.com', 'university' => 'Universitas Sebelas Maret'],
            ['name' => 'Fajar Nugroho', 'email' => 'fajar.nugroho@mail.com', 'university' => 'Universitas Negeri Jakarta'],
            ['name' => 'Ratna Sari Dewi', 'email' => 'ratna.sari@mail.com', 'university' => 'Universitas Andalas'],
            ['name' => 'Hendra Gunawan', 'email' => 'hendra.gunawan@mail.com', 'university' => 'Universitas Sumatera Utara'],
            ['name' => 'Mega Puspita', 'email' => 'mega.puspita@mail.com', 'university' => 'Universitas Lampung'],
            ['name' => 'Arif Rahman Hakim', 'email' => 'arif.rahman@mail.com', 'university' => 'Universitas Sriwijaya'],
            ['name' => 'Yuni Kartika', 'email' => 'yuni.kartika@mail.com', 'university' => 'Universitas Jember'],
            ['name' => 'Bayu Aditya', 'email' => 'bayu.aditya@mail.com', 'university' => 'Universitas Negeri Malang'],
            ['name' => 'Eka Putri Lestari', 'email' => 'eka.putri@mail.com', 'university' => 'Universitas Negeri Surabaya'],
            ['name' => 'Surya Dharma', 'email' => 'surya.dharma@mail.com', 'university' => 'Universitas Pendidikan Indonesia'],
            ['name' => 'Anisa Rahmania', 'email' => 'anisa.rahmania@mail.com', 'university' => 'Universitas Islam Indonesia'],
        ];

        $created = [];
        foreach ($pesertaData as $data) {
            $created[] = User::updateOrCreate(
                ['email' => $data['email']],
                [
                    'name' => $data['name'],
                    'password' => Hash::make('password123'),
                    'role' => 'peserta',
                    'university' => $data['university'],
                    'major' => $this->getRandomMajor(),
                    'semester' => rand(5, 8),
                    'gpa' => round(rand(300, 400) / 100, 2),
                    'is_active' => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        return $created;
    }

    private function createApplications(array $peserta, array $divisions): array
    {
        // Status distribution: 35 pending, 67 accepted, 16 rejected = 118 total
        // Using English status values from enum
        $statusDistribution = [
            'menunggu' => 35,
            'accepted' => 67,
            'rejected' => 16,
        ];

        $applications = [];
        $index = 0;

        foreach ($statusDistribution as $status => $count) {
            for ($i = 0; $i < $count; $i++) {
                $user = $peserta[$index % count($peserta)];
                $division = $divisions[array_rand($divisions)];
                
                // Random date within last 6 months
                $createdAt = Carbon::now()->subDays(rand(0, 180));
                
                $application = Application::create([
                    'user_id' => $user->id,
                    'division_id' => $division->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => '08' . rand(1000000000, 9999999999),
                    'university' => $user->university ?? 'Universitas Indonesia',
                    'major' => $user->major ?? 'Teknik Informatika',
                    'semester' => $user->semester ?? rand(5, 8),
                    'gpa' => $user->gpa ?? round(rand(300, 400) / 100, 2),
                    'status' => $status,
                    'motivation' => $this->getRandomMotivation(),
                    'created_at' => $createdAt,
                    'updated_at' => $createdAt->copy()->addDays(rand(0, 7)),
                ]);

                $applications[] = ['id' => $application->id, 'status' => $status];
                $index++;
            }
        }

        return $applications;
    }

    private function getRandomMajor(): string
    {
        $majors = [
            'Teknik Informatika',
            'Sistem Informasi',
            'Ilmu Komputer',
            'Teknik Elektro',
            'Manajemen',
            'Akuntansi',
            'Statistika',
            'Matematika',
            'Ekonomi',
            'Teknik Industri',
        ];

        return $majors[array_rand($majors)];
    }

    private function getRandomMotivation(): string
    {
        $motivations = [
            'Saya sangat tertarik dengan bidang Business Intelligence dan ingin mengembangkan kemampuan analisis data saya melalui program magang ini.',
            'Saya ingin menerapkan ilmu yang saya pelajari di kampus dalam lingkungan kerja nyata dan berkontribusi pada pengembangan solusi data-driven.',
            'Program magang di GrowithBI adalah kesempatan emas untuk belajar dari para profesional dan memperluas jaringan di industri.',
            'Saya memiliki passion di bidang data analytics dan yakin program ini akan membantu saya mengasah skill yang relevan untuk karir masa depan.',
            'Dengan latar belakang pendidikan saya, saya yakin dapat memberikan kontribusi positif sekaligus belajar banyak hal baru di program ini.',
        ];

        return $motivations[array_rand($motivations)];
    }
}

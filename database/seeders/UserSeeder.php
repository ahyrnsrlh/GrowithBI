<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Division;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create Admin User
        User::create([
            'name' => 'Admin GrowithBI',
            'email' => 'admin@growithbi.com',
            'password' => Hash::make('password123'),
            'role' => 'admin',
            'phone' => '081234567890',
            'address' => 'Jl. M.H. Thamrin No. 2, Jakarta Pusat 10110',
            'is_active' => true,
            'email_verified_at' => now(),
            'bio' => 'Administrator sistem GrowithBI dengan pengalaman lebih dari 5 tahun dalam mengelola platform pembelajaran dan pengembangan.',
            'position' => 'System Administrator',
        ]);

        // Create Akhyar User - Enhanced Profile
        User::create([
            'name' => 'Akhyar Nasrullah',
            'email' => 'akhyar@example.com',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
            'phone' => '081234567899',
            'address' => 'Jl. Sudirman No. 45, Jakarta Selatan 12190',
            'university' => 'Universitas Indonesia',
            'major' => 'Teknik Informatika',
            'semester' => 6,
            'gpa' => 3.75,
            'birth_date' => Carbon::parse('2002-03-15'),
            'gender' => 'male',
            'is_active' => true,
            'email_verified_at' => now(),
            'bio' => 'Mahasiswa Teknik Informatika dengan passion di bidang Business Intelligence dan Data Analytics. Berpengalaman dalam Python, SQL, dan tools visualisasi data.',
            'portfolio_url' => 'https://github.com/akhyarnasrullah',
        ]);

        // Create Pembimbing (Supervisor) Users
        $supervisor1 = User::create([
            'name' => 'Dr. Sarah Wijaya',
            'email' => 'sarah.wijaya@growithbi.com',
            'password' => Hash::make('password123'),
            'role' => 'pembimbing',
            'phone' => '081234567891',
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        $supervisor2 = User::create([
            'name' => 'Ir. Budi Santoso, M.T.',
            'email' => 'budi.santoso@growithbi.com',
            'password' => Hash::make('password123'),
            'role' => 'pembimbing',
            'phone' => '081234567892',
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        // Create Peserta (Intern) Users with Complete Profiles
        $pesertaData = [
            [
                'name' => 'Ahmad Rizki Pratama',
                'email' => 'ahmad.rizki@ui.ac.id',
                'university' => 'Universitas Indonesia',
                'major' => 'Sistem Informasi',
                'semester' => 6,
                'gpa' => 3.82,
                'birth_date' => Carbon::parse('2002-05-20'),
                'gender' => 'male',
                'phone' => '081234567893',
                'address' => 'Jl. Margonda Raya No. 100, Depok 16424',
                'bio' => 'Mahasiswa Sistem Informasi dengan minat pada Business Intelligence dan Data Visualization. Berpengalaman menggunakan Tableau dan Power BI.',
                'portfolio_url' => 'https://github.com/ahmadrizki',
            ],
            [
                'name' => 'Siti Nurhaliza Putri',
                'email' => 'siti.nurhaliza@itb.ac.id',
                'university' => 'Institut Teknologi Bandung',
                'major' => 'Teknik Informatika',
                'semester' => 7,
                'gpa' => 3.91,
                'birth_date' => Carbon::parse('2001-12-08'),
                'gender' => 'female',
                'phone' => '081234567894',
                'address' => 'Jl. Ganesha No. 10, Bandung 40132',
                'bio' => 'Passionate about AI/ML and its applications in financial technology. Active in research on machine learning algorithms for fraud detection.',
                'portfolio_url' => 'https://github.com/sitinurhaliza',
            ],
            [
                'name' => 'Muhammad Farhan Alfarisi',
                'email' => 'farhan.alfarisi@ugm.ac.id',
                'university' => 'Universitas Gadjah Mada',
                'major' => 'Ilmu Komputer',
                'semester' => 5,
                'gpa' => 3.67,
                'birth_date' => Carbon::parse('2002-09-14'),
                'gender' => 'male',
                'phone' => '081234567895',
                'address' => 'Jl. Kaliurang KM 14, Yogyakarta 55281',
                'bio' => 'Computer Science student with strong background in data structures and algorithms. Interested in fintech and digital banking solutions.',
                'portfolio_url' => 'https://github.com/farhanalf',
            ],
            [
                'name' => 'Dewi Lestari Sari',
                'email' => 'dewi.lestari@unpad.ac.id',
                'university' => 'Universitas Padjadjaran',
                'major' => 'Statistika',
                'semester' => 6,
                'gpa' => 3.88,
                'birth_date' => Carbon::parse('2002-02-28'),
                'gender' => 'female',
                'phone' => '081234567896',
                'address' => 'Jl. Raya Bandung-Sumedang, Jatinangor 45363',
                'bio' => 'Statistics student with expertise in data analysis and statistical modeling. Experienced in R, Python, and SPSS for data science projects.',
                'portfolio_url' => 'https://github.com/dewilestari',
            ],
            [
                'name' => 'Bayu Wijaya Kusuma',
                'email' => 'bayu.wijaya@its.ac.id',
                'university' => 'Institut Teknologi Sepuluh Nopember',
                'major' => 'Teknik Informatika',
                'semester' => 7,
                'gpa' => 3.74,
                'birth_date' => Carbon::parse('2001-11-03'),
                'gender' => 'male',
                'phone' => '081234567897',
                'address' => 'Jl. Raya ITS, Sukolilo, Surabaya 60111',
                'bio' => 'Tech enthusiast with focus on web development and database management. Skilled in full-stack development using Laravel and Vue.js.',
                'portfolio_url' => 'https://github.com/bayuwijaya',
            ],
            [
                'name' => 'Anisa Rahma Fitri',
                'email' => 'anisa.rahma@unair.ac.id',
                'university' => 'Universitas Airlangga',
                'major' => 'Matematika',
                'semester' => 5,
                'gpa' => 3.85,
                'birth_date' => Carbon::parse('2002-07-16'),
                'gender' => 'female',
                'phone' => '081234567898',
                'address' => 'Jl. Airlangga No. 4-6, Surabaya 60115',
                'bio' => 'Mathematics student with strong analytical skills. Interested in financial modeling and quantitative analysis for risk management.',
                'portfolio_url' => 'https://github.com/anisarahma',
            ],
            [
                'name' => 'Reza Pratama Putra',
                'email' => 'reza.pratama@undip.ac.id',
                'university' => 'Universitas Diponegoro',
                'major' => 'Teknik Komputer',
                'semester' => 6,
                'gpa' => 3.79,
                'birth_date' => Carbon::parse('2002-04-22'),
                'gender' => 'male',
                'phone' => '081234567899',
                'address' => 'Jl. Prof. Sudarto, Tembalang, Semarang 50275',
                'bio' => 'Computer Engineering student passionate about IoT and embedded systems. Experience in developing smart solutions for banking automation.',
                'portfolio_url' => 'https://github.com/rezapratama',
            ],
            [
                'name' => 'Fitri Ayu Lestari',
                'email' => 'fitri.ayu@ub.ac.id',
                'university' => 'Universitas Brawijaya',
                'major' => 'Teknologi Informasi',
                'semester' => 5,
                'gpa' => 3.72,
                'birth_date' => Carbon::parse('2002-10-11'),
                'gender' => 'female',
                'phone' => '081234567900',
                'address' => 'Jl. Veteran, Malang 65145',
                'bio' => 'IT student with interest in cybersecurity and digital forensics. Active in developing secure banking applications and fraud prevention systems.',
                'portfolio_url' => 'https://github.com/fitriayu',
            ]
        ];

        foreach ($pesertaData as $data) {
            User::create(array_merge($data, [
                'password' => Hash::make('password123'),
                'role' => 'peserta',
                'is_active' => true,
                'email_verified_at' => now(),
            ]));
        }

        $this->command->info('Created ' . (count($pesertaData) + 4) . ' users successfully!');
    }
}

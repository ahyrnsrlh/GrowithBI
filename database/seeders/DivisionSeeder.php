<?php

namespace Database\Seeders;

use App\Models\Division;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $supervisors = User::where('role', 'pembimbing')->get();

        $divisions = [
            [
                'name' => 'Business Intelligence & Analytics',
                'description' => 'Fokus pada analisis data, pembuatan dashboard, dan business intelligence untuk mendukung pengambilan keputusan strategis.',
                'requirements' => 'Mahasiswa jurusan Informatika, Sistem Informasi, Statistika, atau Data Science dengan kemampuan SQL dan tools analytics.',
                'max_interns' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Software Development',
                'description' => 'Pengembangan aplikasi web dan mobile menggunakan teknologi modern seperti Laravel, Vue.js, React, dan Flutter.',
                'requirements' => 'Mahasiswa jurusan Informatika atau Teknik Komputer dengan pemahaman programming web/mobile.',
                'max_interns' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Data Science & Machine Learning',
                'description' => 'Penelitian dan pengembangan model machine learning untuk prediksi bisnis dan otomatisasi proses.',
                'requirements' => 'Mahasiswa jurusan Data Science, Statistika, Matematika dengan kemampuan Python/R dan ML frameworks.',
                'max_interns' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'UI/UX Design',
                'description' => 'Desain antarmuka pengguna dan pengalaman pengguna untuk aplikasi web dan mobile.',
                'requirements' => 'Mahasiswa Desain Komunikasi Visual, HCI, atau yang memiliki portfolio UI/UX design.',
                'max_interns' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Digital Marketing & Growth',
                'description' => 'Strategi pemasaran digital, SEO, content marketing, dan growth hacking untuk produk digital.',
                'requirements' => 'Mahasiswa Komunikasi, Manajemen, atau Marketing dengan minat di digital marketing.',
                'max_interns' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Quality Assurance & Testing',
                'description' => 'Testing aplikasi, automation testing, dan quality assurance untuk memastikan kualitas produk.',
                'requirements' => 'Mahasiswa Informatika atau Teknik Komputer dengan pemahaman software testing.',
                'max_interns' => 4,
                'is_active' => true,
            ],
        ];

        foreach ($divisions as $index => $divisionData) {
            $supervisor = $supervisors->get($index % $supervisors->count());
            
            Division::create(array_merge($divisionData, [
                'supervisor_id' => $supervisor->id
            ]));
        }
    }
}

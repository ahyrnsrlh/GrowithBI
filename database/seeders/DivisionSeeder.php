<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Division;

class DivisionSeeder extends Seeder
{
    public function run()
    {
        Division::create([
            'name' => 'Business Intelligence Banking',
            'description' => 'Program magang yang berfokus pada analisis data perbankan, dashboard BI, dan pengembangan insights untuk mendukung pengambilan keputusan strategis di sektor perbankan.',
            'icon' => 'fas fa-chart-line'
        ]);

        Division::create([
            'name' => 'Data Analytics & Research',
            'description' => 'Program magang untuk mengembangkan kemampuan analisis data ekonomi dan keuangan, riset pasar, serta pemodelan statistik untuk mendukung kebijakan Bank Indonesia.',
            'icon' => 'fas fa-search-dollar'
        ]);

        Division::create([
            'name' => 'Financial Technology',
            'description' => 'Program magang yang mengeksplorasi teknologi finansial terkini, payment systems, digital banking, dan inovasi teknologi dalam sektor keuangan.',
            'icon' => 'fas fa-mobile-alt'
        ]);
    }
}

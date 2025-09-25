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

        Division::create([
            'name' => 'Risk Management Analytics',
            'description' => 'Program magang yang berfokus pada analisis risiko kredit, risiko pasar, dan pengembangan model prediktif untuk manajemen risiko perbankan.',
            'icon' => 'fas fa-shield-alt'
        ]);

        Division::create([
            'name' => 'Digital Transformation',
            'description' => 'Program magang untuk mendukung transformasi digital perbankan, implementasi teknologi cloud, dan pengembangan solusi digital banking.',
            'icon' => 'fas fa-digital-tachograph'
        ]);

        Division::create([
            'name' => 'Customer Experience Analytics',
            'description' => 'Program magang yang menganalisis perilaku nasabah, customer journey mapping, dan pengembangan strategi untuk meningkatkan kepuasan pelanggan.',
            'icon' => 'fas fa-users'
        ]);

        Division::create([
            'name' => 'Regulatory Technology (RegTech)',
            'description' => 'Program magang yang berfokus pada compliance monitoring, regulatory reporting automation, dan pengembangan solusi teknologi untuk kepatuhan perbankan.',
            'icon' => 'fas fa-gavel'
        ]);

        Division::create([
            'name' => 'Artificial Intelligence & Machine Learning',
            'description' => 'Program magang untuk pengembangan model AI/ML dalam fraud detection, credit scoring, chatbot development, dan automated decision making.',
            'icon' => 'fas fa-robot'
        ]);
    }
}

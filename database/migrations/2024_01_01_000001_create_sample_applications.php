<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Create sample applications for testing charts
        $users = App\Models\User::where('role', 'peserta')->get();
        $divisions = App\Models\Division::where('is_active', true)->get();
        
        if ($users->count() > 0 && $divisions->count() > 0) {
            $statuses = ['menunggu', 'diterima', 'ditolak'];
            
            for ($i = 0; $i < 20; $i++) {
                App\Models\Application::create([
                    'user_id' => $users->random()->id,
                    'division_id' => $divisions->random()->id,
                    'status' => $statuses[array_rand($statuses)],
                    'cv_file' => 'sample_cv_' . ($i + 1) . '.pdf',
                    'ktp_file' => 'sample_ktp_' . ($i + 1) . '.jpg',
                    'application_letter_file' => 'sample_letter_' . ($i + 1) . '.pdf',
                    'motivation_letter' => 'Sample motivation for testing application ' . ($i + 1),
                    'created_at' => now()->subDays(rand(0, 30)),
                    'updated_at' => now()->subDays(rand(0, 30)),
                ]);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        App\Models\Application::where('motivation_letter', 'like', 'Sample motivation for testing%')->delete();
    }
};

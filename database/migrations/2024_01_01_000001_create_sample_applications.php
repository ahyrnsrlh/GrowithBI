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
            $statuses = ['pending', 'approved', 'rejected'];
            
            for ($i = 0; $i < 20; $i++) {
                App\Models\Application::create([
                    'user_id' => $users->random()->id,
                    'division_id' => $divisions->random()->id,
                    'name' => 'Test Application ' . ($i + 1),
                    'email' => 'test' . ($i + 1) . '@example.com',
                    'phone' => '08123456789',
                    'motivation' => 'Sample motivation for testing',
                    'experience' => 'Sample experience for testing',
                    'status' => $statuses[array_rand($statuses)],
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
        App\Models\Application::where('email', 'like', 'test%@example.com')->delete();
    }
};

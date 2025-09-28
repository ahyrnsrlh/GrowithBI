<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Application;
use App\Models\User;
use App\Models\Division;

class SampleApplicationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get existing users and divisions
        $users = User::where('role', 'peserta')->get();
        $divisions = Division::where('is_active', true)->get();
        
        if ($users->count() === 0 || $divisions->count() === 0) {
            $this->command->info('No users or divisions found. Creating sample data...');
            
            // Create sample divisions if none exist
            if ($divisions->count() === 0) {
                $sampleDivisions = [
                    ['name' => 'IT Development', 'description' => 'Software Development Division', 'max_interns' => 5],
                    ['name' => 'Digital Marketing', 'description' => 'Marketing and Social Media', 'max_interns' => 3],
                    ['name' => 'Data Analytics', 'description' => 'Business Intelligence', 'max_interns' => 4],
                    ['name' => 'UI/UX Design', 'description' => 'User Interface Design', 'max_interns' => 2],
                ];
                
                foreach ($sampleDivisions as $div) {
                    Division::create($div);
                }
                
                $divisions = Division::all();
            }
            
            // Create sample users if none exist
            if ($users->count() === 0) {
                for ($i = 1; $i <= 10; $i++) {
                    User::create([
                        'name' => 'Peserta ' . $i,
                        'email' => 'peserta' . $i . '@example.com',
                        'password' => bcrypt('password'),
                        'role' => 'peserta',
                        'is_active' => true,
                    ]);
                }
                
                $users = User::where('role', 'peserta')->get();
            }
        }
        
        // Create sample applications with different statuses
        $statuses = ['menunggu', 'dalam_review', 'wawancara', 'diterima', 'ditolak'];
        $statusWeights = [
            'menunggu' => 6,      // 30%
            'dalam_review' => 4,  // 20%
            'wawancara' => 3,     // 15%
            'diterima' => 4,      // 20%
            'ditolak' => 3,       // 15%
        ];
        
        // Create weighted status array
        $weightedStatuses = [];
        foreach ($statusWeights as $status => $weight) {
            for ($i = 0; $i < $weight; $i++) {
                $weightedStatuses[] = $status;
            }
        }
        
        for ($i = 1; $i <= 20; $i++) {
            Application::create([
                'user_id' => $users->random()->id,
                'division_id' => $divisions->random()->id,
                'name' => 'Test Applicant ' . $i,
                'email' => 'applicant' . $i . '@example.com',
                'phone' => '081234567' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'motivation' => 'I am very motivated to join this internship program and contribute to the company growth.',
                'status' => $weightedStatuses[array_rand($weightedStatuses)],
                'created_at' => now()->subDays(rand(0, 60)),
                'updated_at' => now()->subDays(rand(0, 30)),
            ]);
        }
        
        $this->command->info('Sample applications created successfully!');
    }
}

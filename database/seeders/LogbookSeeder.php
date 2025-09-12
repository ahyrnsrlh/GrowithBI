<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Logbook;
use App\Models\User;
use App\Models\Division;
use Carbon\Carbon;

class LogbookSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Get users with role peserta
        $pesertaUsers = User::where('role', 'peserta')->get();
        $divisions = Division::all();

        if ($pesertaUsers->isEmpty() || $divisions->isEmpty()) {
            $this->command->info('No peserta users or divisions found. Skipping logbook seeding.');
            return;
        }

        // Create sample logbook entries for each peserta
        foreach ($pesertaUsers as $peserta) {
            // Assign peserta to a random division
            $division = $divisions->random();
            
            // Create logbook entries for the past 2 weeks
            for ($i = 14; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                $activities = [
                    'Menganalisis data penjualan dan membuat laporan trend bulanan',
                    'Membantu dalam pembuatan dashboard business intelligence',
                    'Melakukan data cleaning dan preprocessing untuk analisis',
                    'Mengikuti meeting tim dan presentasi project',
                    'Belajar menggunakan tools SQL dan Power BI',
                    'Membuat dokumentasi proses analisis data',
                    'Melakukan penelitian kompetitor dan market analysis',
                    'Membantu dalam pembuatan report eksekutif',
                    'Testing dan validasi model prediksi',
                    'Mengorganisir dan mengarsip data historical'
                ];

                $notes = [
                    'Hari ini sangat produktif, berhasil menyelesaikan target',
                    'Belajar banyak hal baru tentang data visualization',
                    'Mendapat feedback positif dari supervisor',
                    'Menghadapi sedikit kendala tapi berhasil diatasi',
                    'Kolaborasi tim berjalan dengan baik',
                    null, // Some entries might not have notes
                    'Perlu belajar lebih dalam tentang advanced SQL',
                    'Presentasi berjalan lancar dan mendapat apresiasi'
                ];

                $status = ['draft', 'submitted', 'approved'];
                $selectedStatus = fake()->randomElement($status);
                
                $submittedAt = null;
                $approvedAt = null;
                $approvedBy = null;

                if ($selectedStatus === 'submitted' || $selectedStatus === 'approved') {
                    $submittedAt = $date->copy()->addHours(17); // Submitted at 5 PM
                }

                if ($selectedStatus === 'approved') {
                    $approvedAt = $date->copy()->addDay()->addHours(9); // Approved next day at 9 AM
                    $approvedBy = User::where('role', 'pembimbing')->inRandomOrder()->first()?->id;
                }

                Logbook::create([
                    'user_id' => $peserta->id,
                    'division_id' => $division->id,
                    'title' => 'Daily Activities - ' . $date->format('Y-m-d'),
                    'date' => $date->toDateString(),
                    'duration' => 8.0, // 8 hours
                    'time_in' => '08:00:00',
                    'time_out' => '17:00:00',
                    'activities' => fake()->randomElement($activities),
                    'notes' => fake()->randomElement($notes),
                    'status' => $selectedStatus,
                    'supervisor_notes' => $selectedStatus === 'approved' ? 'Good work! Keep it up.' : null,
                    'submitted_at' => $submittedAt,
                    'approved_at' => $approvedAt,
                    'approved_by' => $approvedBy,
                    'created_at' => $date,
                    'updated_at' => $date,
                ]);
            }
        }

        $this->command->info('Logbook entries created successfully!');
    }
}

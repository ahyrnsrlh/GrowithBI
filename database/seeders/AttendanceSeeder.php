<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Faker\Factory as Faker;

class AttendanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create();
        
        // Get participants with accepted applications
        $participants = User::where('role', 'peserta')
            ->whereHas('applications', function ($q) {
                $q->where('status', 'diterima');
            })
            ->get();

        if ($participants->isEmpty()) {
            $this->command->info('No participants with accepted applications found. Skipping attendance seeding.');
            return;
        }

        // Bank Indonesia coordinates (example)
        $officeLatitude = -5.397140;
        $officeLongitude = 105.266792;

        foreach ($participants as $participant) {
            // Generate attendance for the last 30 days
            for ($i = 29; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                // Skip weekends
                if ($date->isWeekend()) {
                    continue;
                }

                // 90% chance of attendance
                if ($faker->randomFloat(2, 0, 1) > 0.9) {
                    // Absent
                    Attendance::create([
                        'user_id' => $participant->id,
                        'date' => $date->toDateString(),
                        'status' => 'Absent',
                    ]);
                    continue;
                }

                // Generate random office hours check-in (7:30 - 9:00 AM)
                $checkInHour = $faker->numberBetween(7, 8);
                $checkInMinute = $faker->numberBetween(0, 59);
                
                // If after 8:00 AM, it's late
                $isLate = $checkInHour > 8 || ($checkInHour == 8 && $checkInMinute > 0);
                $status = $isLate ? 'Late' : 'On-Time';

                $checkIn = $date->copy()->setTime($checkInHour, $checkInMinute, $faker->numberBetween(0, 59));
                
                // Generate check-out (4:00 - 6:00 PM)
                $checkOut = null;
                if ($faker->randomFloat(2, 0, 1) > 0.1) { // 90% chance of check-out
                    $checkOutHour = $faker->numberBetween(16, 18);
                    $checkOutMinute = $faker->numberBetween(0, 59);
                    $checkOut = $date->copy()->setTime($checkOutHour, $checkOutMinute, $faker->numberBetween(0, 59));
                } else {
                    $status = 'Not-Checked-Out';
                }

                // Add some variance to coordinates (within 50m of office)
                $latVariance = $faker->randomFloat(6, -0.0005, 0.0005);
                $lonVariance = $faker->randomFloat(6, -0.0005, 0.0005);

                Attendance::create([
                    'user_id' => $participant->id,
                    'date' => $date->toDateString(),
                    'check_in' => $checkIn,
                    'check_out' => $checkOut,
                    'status' => $status,
                    'latitude' => $officeLatitude + $latVariance,
                    'longitude' => $officeLongitude + $lonVariance,
                    'location_address' => 'Bank Indonesia KPw Lampung',
                    'notes' => $faker->optional(0.2)->sentence(), // 20% chance of notes
                ]);
            }
        }

        $this->command->info('Attendance records seeded successfully.');
    }
}

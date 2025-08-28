<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Division;
use App\Models\Application;

class ApplicationTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create test users
        $user1 = User::create([
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'role' => 'peserta',
            'phone' => '08123456789',
            'address' => 'Jakarta',
            'university' => 'Universitas Indonesia',
            'major' => 'Informatika',
            'semester' => 6,
            'gpa' => 3.50,
        ]);

        $user2 = User::create([
            'name' => 'Jane Smith',
            'email' => 'jane@example.com',
            'password' => bcrypt('password'),
            'role' => 'peserta',
            'phone' => '08123456788',
            'address' => 'Bandung',
            'university' => 'ITB',
            'major' => 'Sistem Informasi',
            'semester' => 5,
            'gpa' => 3.75,
        ]);

        $user3 = User::create([
            'name' => 'Bob Wilson',
            'email' => 'bob@example.com',
            'password' => bcrypt('password'),
            'role' => 'peserta',
            'phone' => '08123456787',
            'address' => 'Surabaya',
            'university' => 'ITS',
            'major' => 'Teknik Informatika',
            'semester' => 7,
            'gpa' => 3.25,
        ]);

        // Get divisions
        $divisions = Division::all();

        if ($divisions->count() > 0) {
            // User 1 has pending application (should not be able to apply elsewhere)
            Application::create([
                'user_id' => $user1->id,
                'name' => $user1->name,
                'email' => $user1->email,
                'phone' => $user1->phone,
                'address' => $user1->address,
                'university' => $user1->university,
                'major' => $user1->major,
                'semester' => $user1->semester,
                'gpa' => $user1->gpa,
                'division_id' => $divisions->first()->id,
                'motivation' => 'I am very interested in joining this internship program to gain practical experience in my field.',
                'status' => 'menunggu',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(7),
            ]);

            // User 2 has accepted application (should not be able to apply elsewhere)
            Application::create([
                'user_id' => $user2->id,
                'name' => $user2->name,
                'email' => $user2->email,
                'phone' => $user2->phone,
                'address' => $user2->address,
                'university' => $user2->university,
                'major' => $user2->major,
                'semester' => $user2->semester,
                'gpa' => $user2->gpa,
                'division_id' => $divisions->skip(1)->first()?->id ?? $divisions->first()->id,
                'motivation' => 'I am excited about this opportunity to learn and contribute to your team.',
                'status' => 'diterima',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(7),
            ]);

            // User 3 has rejected application (should be able to apply again)
            Application::create([
                'user_id' => $user3->id,
                'name' => $user3->name,
                'email' => $user3->email,
                'phone' => $user3->phone,
                'address' => $user3->address,
                'university' => $user3->university,
                'major' => $user3->major,
                'semester' => $user3->semester,
                'gpa' => $user3->gpa,
                'division_id' => $divisions->skip(2)->first()?->id ?? $divisions->first()->id,
                'motivation' => 'I believe this internship will help me develop my skills in the industry.',
                'status' => 'ditolak',
                'start_date' => now()->addMonth(),
                'end_date' => now()->addMonths(7),
            ]);
        }

        $this->command->info('Test application data created successfully!');
        $this->command->info('- John Doe: Has pending application (cannot apply elsewhere)');
        $this->command->info('- Jane Smith: Has accepted application (cannot apply elsewhere)');
        $this->command->info('- Bob Wilson: Has rejected application (can apply again)');
    }
}

<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

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
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
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

        // Create Peserta (Intern) Users
        User::create([
            'name' => 'Ahmad Rizki Pratama',
            'email' => 'ahmad.rizki@student.university.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
            'supervisor_id' => $supervisor1->id,
            'phone' => '081234567893',
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Siti Nurhaliza',
            'email' => 'siti.nurhaliza@student.university.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
            'supervisor_id' => $supervisor1->id,
            'phone' => '081234567894',
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Muhammad Farhan',
            'email' => 'muhammad.farhan@student.university.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
            'supervisor_id' => $supervisor2->id,
            'phone' => '081234567895',
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);

        User::create([
            'name' => 'Dewi Lestari',
            'email' => 'dewi.lestari@student.university.ac.id',
            'password' => Hash::make('password123'),
            'role' => 'peserta',
            'supervisor_id' => $supervisor2->id,
            'phone' => '081234567896',
            'address' => 'Jakarta, Indonesia',
            'is_active' => true,
            'email_verified_at' => now(),
        ]);
    }
}

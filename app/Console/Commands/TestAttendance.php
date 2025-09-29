<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Attendance;

class TestAttendance extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'attendance:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test attendance system functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Attendance System...');
        $this->newLine();

        // Test 1: Check users with attendance access
        $participantsWithAccess = User::where('role', 'peserta')
            ->whereHas('applications', function ($q) {
                $q->where('status', 'diterima');
            })
            ->count();
        
        $this->info("✓ Participants with attendance access: {$participantsWithAccess}");

        // Test 2: Check attendance records
        $totalAttendance = Attendance::count();
        $this->info("✓ Total attendance records: {$totalAttendance}");

        // Test 3: Check today's attendance
        $todayAttendance = Attendance::where('date', now()->toDateString())->count();
        $this->info("✓ Today's attendance records: {$todayAttendance}");

        // Test 4: Check attendance statistics
        $stats = [
            'on_time' => Attendance::where('status', 'On-Time')->count(),
            'late' => Attendance::where('status', 'Late')->count(),
            'absent' => Attendance::where('status', 'Absent')->count(),
            'not_checked_out' => Attendance::where('status', 'Not-Checked-Out')->count(),
        ];

        $this->newLine();
        $this->info('Attendance Statistics:');
        $this->table(
            ['Status', 'Count'],
            [
                ['On-Time', $stats['on_time']],
                ['Late', $stats['late']],
                ['Absent', $stats['absent']],
                ['Not Checked-Out', $stats['not_checked_out']],
            ]
        );

        // Test 5: Sample user attendance access
        $sampleUser = User::where('role', 'peserta')
            ->whereHas('applications', function ($q) {
                $q->where('status', 'diterima');
            })
            ->first();

        if ($sampleUser) {
            $this->newLine();
            $this->info("Testing user attendance access for: {$sampleUser->name}");
            $canAccess = $sampleUser->canAccessAttendance();
            $this->info($canAccess ? '✓ User can access attendance' : '✗ User cannot access attendance');
            
            $userStats = $sampleUser->getAttendanceStats();
            $this->info("User's attendance stats: " . json_encode($userStats));
        }

        $this->newLine();
        $this->info('Attendance system test completed successfully!');
        
        return Command::SUCCESS;
    }
}

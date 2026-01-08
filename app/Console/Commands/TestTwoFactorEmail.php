<?php

namespace App\Console\Commands;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

/**
 * TestTwoFactorEmail Command
 * 
 * Test command to verify 2FA email sending functionality
 */
class TestTwoFactorEmail extends Command
{
    protected $signature = 'twofactor:test-email {email?} {--user-id=}';
    protected $description = 'Test sending 2FA OTP email';

    public function handle(): int
    {
        // Get user
        if ($this->option('user-id')) {
            $user = User::find($this->option('user-id'));
        } elseif ($this->argument('email')) {
            $user = User::where('email', $this->argument('email'))->first();
        } else {
            $user = User::where('role', 'admin')->first();
        }

        if (!$user) {
            $this->error('User not found!');
            return Command::FAILURE;
        }

        $this->info("Sending test OTP email to: {$user->email}");
        
        // Generate test OTP
        $testOtp = '123456';
        
        try {
            Mail::to($user->email)->send(
                new TwoFactorCodeMail($user, $testOtp, 5)
            );
            
            $this->info('✓ Email sent successfully!');
            $this->line("Test OTP Code: {$testOtp}");
            $this->line("Check your email: {$user->email}");
            
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $this->error('Failed to send email!');
            $this->error($e->getMessage());
            return Command::FAILURE;
        }
    }
}

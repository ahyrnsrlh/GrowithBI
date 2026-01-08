<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\TwoFactorService;
use Illuminate\Console\Command;
use Illuminate\Http\Request;

class GenerateTestOtp extends Command
{
    protected $signature = 'twofactor:generate {user-id}';
    protected $description = 'Generate a new OTP for testing';

    public function handle(TwoFactorService $twoFactorService)
    {
        $userId = $this->argument('user-id');
        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found!');
            return Command::FAILURE;
        }

        // Create a mock request
        $request = Request::create('/', 'POST', [], [], [], [
            'REMOTE_ADDR' => '127.0.0.1',
            'HTTP_USER_AGENT' => 'Console Test'
        ]);

        // Generate OTP
        $this->info("Generating OTP for: {$user->name} ({$user->email})");
        $code = $twoFactorService->sendOtpCode($user, $request);

        $this->info('✓ OTP generated and email queued!');
        $this->line('');
        $this->info('Code ID: ' . $code->id);
        $this->info('Expires at: ' . $code->expires_at->format('Y-m-d H:i:s'));
        $this->info('Check your email or run queue worker: php artisan queue:work');
        $this->line('');
        $this->info('Then check logs: Get-Content storage\logs\laravel.log | Select-String "2FA OTP Generated" -Context 0,3 | Select-Object -Last 1');

        return Command::SUCCESS;
    }
}

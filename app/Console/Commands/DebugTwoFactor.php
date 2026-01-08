<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\TwoFactorCode;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class DebugTwoFactor extends Command
{
    protected $signature = 'twofactor:debug {user-id}';
    protected $description = 'Debug 2FA codes for a user';

    public function handle()
    {
        $userId = $this->argument('user-id');
        $user = User::find($userId);

        if (!$user) {
            $this->error('User not found!');
            return Command::FAILURE;
        }

        $this->info("User: {$user->name} ({$user->email})");
        $this->info("Role: {$user->role}");
        $this->line('');

        $codes = TwoFactorCode::where('user_id', $userId)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        if ($codes->isEmpty()) {
            $this->warn('No OTP codes found for this user.');
            return Command::SUCCESS;
        }

        $this->info('Recent OTP Codes:');
        $this->table(
            ['ID', 'Created', 'Expires', 'Attempts', 'Used', 'Valid?'],
            $codes->map(function ($code) {
                return [
                    $code->id,
                    $code->created_at->format('Y-m-d H:i:s'),
                    $code->expires_at->format('Y-m-d H:i:s'),
                    $code->attempts,
                    $code->used_at ? 'Yes' : 'No',
                    $code->isValid() ? '✓' : '✗',
                ];
            })
        );

        // Test hash verification
        $latestCode = $codes->first();
        if ($latestCode && $latestCode->isValid()) {
            $this->line('');
            $this->info('Testing latest valid code:');
            $testCode = $this->ask('Enter the OTP code from your email to test');
            
            if ($testCode) {
                $matches = Hash::check($testCode, $latestCode->code_hash);
                $this->line("Hash check result: " . ($matches ? '✓ MATCH' : '✗ NO MATCH'));
                
                if (!$matches) {
                    $this->warn('The code does not match!');
                    $this->line('Code hash: ' . substr($latestCode->code_hash, 0, 50) . '...');
                }
            }
        }

        return Command::SUCCESS;
    }
}

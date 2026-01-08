<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;

class TestHashVerification extends Command
{
    protected $signature = 'test:hash';
    protected $description = 'Test hash generation and verification';

    public function handle()
    {
        $this->info('Testing Hash Functionality');
        $this->line('');

        // Test 1: Generate and verify immediately
        $plainCode = '642979';
        $hash = Hash::make($plainCode);
        
        $this->info("Plain code: {$plainCode}");
        $this->info("Generated hash: {$hash}");
        $this->info("Hash length: " . strlen($hash));
        $this->line('');

        // Verify immediately
        $result = Hash::check($plainCode, $hash);
        $this->line("Immediate verification: " . ($result ? '✓ MATCH' : '✗ NO MATCH'));
        $this->line('');

        // Test 2: Try the database hash
        $dbHash = '$2y$12$yAOqDwRtN2Va4qUUIEdcOetg4OOs6//rWw.7J9VFwdNrWwX3p58dy';
        $this->info("Testing database hash:");
        $this->info("DB Hash: {$dbHash}");
        $this->info("DB Hash length: " . strlen($dbHash));
        
        try {
            $result = Hash::check($plainCode, $dbHash);
            $this->line("DB hash verification: " . ($result ? '✓ MATCH' : '✗ NO MATCH'));
        } catch (\Exception $e) {
            $this->error("Error: " . $e->getMessage());
        }

        $this->line('');

        // Test 3: Check hash info
        $this->info("Hash info analysis:");
        $info = password_get_info($hash);
        $this->line("Algorithm: " . $info['algoName']);
        $this->line("Options: " . json_encode($info['options']));

        $this->line('');
        $dbInfo = password_get_info($dbHash);
        $this->line("DB Hash Algorithm: " . $dbInfo['algoName']);
        $this->line("DB Hash Options: " . json_encode($dbInfo['options']));

        return Command::SUCCESS;
    }
}

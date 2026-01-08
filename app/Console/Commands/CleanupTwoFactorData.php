<?php

namespace App\Console\Commands;

use App\Models\TwoFactorCode;
use App\Models\TrustedDevice;
use App\Models\TwoFactorAuditLog;
use Illuminate\Console\Command;

/**
 * CleanupTwoFactorData Command
 * 
 * Scheduled command to clean up expired 2FA data:
 * - Expired OTP codes
 * - Expired trusted devices
 * - Old audit logs (based on retention policy)
 */
class CleanupTwoFactorData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'twofactor:cleanup 
                            {--codes : Clean up expired OTP codes only}
                            {--devices : Clean up expired trusted devices only}
                            {--audit : Clean up old audit logs only}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired two-factor authentication data';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $cleanCodes = $this->option('codes') || !$this->hasAnyOption();
        $cleanDevices = $this->option('devices') || !$this->hasAnyOption();
        $cleanAudit = $this->option('audit') || !$this->hasAnyOption();

        $this->info('Starting two-factor authentication data cleanup...');

        if ($cleanCodes) {
            $codesDeleted = $this->cleanupExpiredCodes();
            $this->line("  - Deleted {$codesDeleted} expired OTP codes");
        }

        if ($cleanDevices) {
            $devicesDeleted = $this->cleanupExpiredDevices();
            $this->line("  - Deleted {$devicesDeleted} expired trusted devices");
        }

        if ($cleanAudit) {
            $logsDeleted = $this->cleanupOldAuditLogs();
            $this->line("  - Deleted {$logsDeleted} old audit log entries");
        }

        $this->info('Cleanup completed successfully!');

        return Command::SUCCESS;
    }

    /**
     * Check if any specific option was provided.
     */
    private function hasAnyOption(): bool
    {
        return $this->option('codes') || $this->option('devices') || $this->option('audit');
    }

    /**
     * Clean up expired OTP codes (older than 1 day past expiration).
     */
    private function cleanupExpiredCodes(): int
    {
        return TwoFactorCode::where('expires_at', '<', now()->subDay())
            ->delete();
    }

    /**
     * Clean up expired trusted devices.
     */
    private function cleanupExpiredDevices(): int
    {
        return TrustedDevice::where('expires_at', '<', now())
            ->delete();
    }

    /**
     * Clean up old audit logs based on retention policy.
     */
    private function cleanupOldAuditLogs(): int
    {
        $retentionDays = config('two_factor.audit.retention_days', 90);
        
        if ($retentionDays === null) {
            return 0; // Never delete
        }

        return TwoFactorAuditLog::where('created_at', '<', now()->subDays($retentionDays))
            ->delete();
    }
}

<?php

namespace App\Jobs;

use App\Mail\TwoFactorCodeMail;
use App\Models\User;
use App\Models\TwoFactorCode;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * SendTwoFactorOtpJob
 * 
 * Queued job to send OTP code via email.
 * 
 * Security notes:
 * - OTP is passed as plaintext only during email sending
 * - Job is processed immediately with high priority
 * - Failures are logged for security monitoring
 */
class SendTwoFactorOtpJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public User $user;
    public string $otpCode;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    /**
     * Create a new job instance.
     */
    public function __construct(User $user, string $otpCode)
    {
        $this->user = $user;
        $this->otpCode = $otpCode;
        
        // Set high priority queue for OTP delivery
        $this->onQueue('high');
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        try {
            Mail::to($this->user->email)->send(
                new TwoFactorCodeMail(
                    $this->user,
                    $this->otpCode,
                    TwoFactorCode::EXPIRES_IN_MINUTES
                )
            );

            Log::info('Two-factor OTP email sent', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
            ]);
        } catch (\Exception $e) {
            Log::error('Failed to send two-factor OTP email', [
                'user_id' => $this->user->id,
                'email' => $this->user->email,
                'error' => $e->getMessage(),
            ]);

            throw $e;
        }
    }

    /**
     * Handle a job failure.
     */
    public function failed(\Throwable $exception): void
    {
        Log::critical('Two-factor OTP email job failed permanently', [
            'user_id' => $this->user->id,
            'email' => $this->user->email,
            'error' => $exception->getMessage(),
        ]);
    }
}

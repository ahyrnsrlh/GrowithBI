<?php

namespace App\Listeners;

use App\Enums\RegistrationEventType;
use App\Enums\RegistrationStatus;
use App\Events\AcceptanceLetterUploaded;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Listener for handling acceptance letter upload notifications.
 * 
 * This listener:
 * 1. Updates application status to 'letter_ready' if currently 'accepted'
 * 2. Notifies the user that their acceptance letter is available for download
 */
class SendAcceptanceLetterNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public string $connection = 'sync';
    public string $queue = 'notifications';
    public int $tries = 3;
    public int $backoff = 60;

    /**
     * Handle the event.
     */
    public function handle(AcceptanceLetterUploaded $event): void
    {
        $idempotencyKey = $event->getIdempotencyKey();
        $cacheKey = 'notification_sent_' . $idempotencyKey;
        
        if (Cache::has($cacheKey)) {
            Log::info('Skipping duplicate acceptance letter notification', ['key' => $idempotencyKey]);
            return;
        }

        try {
            $application = $event->application;
            
            // Update status to letter_ready if currently accepted
            if ($application->status === RegistrationStatus::ACCEPTED->value) {
                $application->update(['status' => RegistrationStatus::LETTER_READY->value]);
                $application->refresh();
            }

            // Notify the user that their acceptance letter is ready
            $user = $application->user;
            
            if ($user) {
                $notification = new RegistrationStatusNotification(
                    application: $application,
                    eventType: RegistrationEventType::ACCEPTANCE_LETTER_READY,
                    metadata: [
                        'download_url' => route('acceptance-letter.download', $application->id),
                        'uploaded_by' => $event->uploadedBy->name,
                    ],
                    isAdminNotification: false
                );

                $user->notify($notification);
            }

            Cache::put($cacheKey, true, now()->addHours(24));

            Log::info('Acceptance letter notification sent', [
                'application_id' => $application->id,
                'user_id' => $application->user_id,
                'status_updated' => $application->status,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send acceptance letter notification', [
                'application_id' => $event->application->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    public function shouldQueue(AcceptanceLetterUploaded $event): bool
    {
        return true;
    }

    public function failed(AcceptanceLetterUploaded $event, \Throwable $exception): void
    {
        Log::error('Acceptance letter notification failed permanently', [
            'application_id' => $event->application->id,
            'error' => $exception->getMessage(),
        ]);
    }
}

<?php

namespace App\Listeners;

use App\Enums\RegistrationEventType;
use App\Events\NewApplicationSubmitted;
use App\Models\User;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Listener for handling new application submission notifications.
 */
class SendNewApplicationNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public string $connection = 'sync';
    public string $queue = 'notifications';
    public int $tries = 3;
    public int $backoff = 60;

    /**
     * Handle the event.
     */
    public function handle(NewApplicationSubmitted $event): void
    {
        $idempotencyKey = $event->getIdempotencyKey();
        $cacheKey = 'notification_sent_' . $idempotencyKey;
        
        if (Cache::has($cacheKey)) {
            Log::info('Skipping duplicate new application notification', ['key' => $idempotencyKey]);
            return;
        }

        try {
            // 1. Notify the user (confirmation)
            $this->notifyUser($event);

            // 2. Notify all admins (new application alert)
            $this->notifyAdmins($event);

            // Mark as sent
            Cache::put($cacheKey, true, now()->addHours(24));

            Log::info('New application notification sent', [
                'application_id' => $event->application->id,
                'user_id' => $event->application->user_id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send new application notification', [
                'application_id' => $event->application->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    private function notifyUser(NewApplicationSubmitted $event): void
    {
        $user = $event->application->user;
        
        if (!$user) {
            return;
        }

        $notification = new RegistrationStatusNotification(
            application: $event->application,
            eventType: RegistrationEventType::APPLICATION_SUBMITTED,
            metadata: $event->metadata,
            isAdminNotification: false
        );

        $user->notify($notification);
    }

    private function notifyAdmins(NewApplicationSubmitted $event): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $notification = new RegistrationStatusNotification(
                application: $event->application,
                eventType: RegistrationEventType::NEW_REGISTRATION,
                metadata: $event->metadata,
                isAdminNotification: true
            );

            $admin->notify($notification);
        }
    }

    public function shouldQueue(NewApplicationSubmitted $event): bool
    {
        return true;
    }

    public function failed(NewApplicationSubmitted $event, \Throwable $exception): void
    {
        Log::error('New application notification failed permanently', [
            'application_id' => $event->application->id,
            'error' => $exception->getMessage(),
        ]);
    }
}

<?php

namespace App\Listeners;

use App\Enums\RegistrationEventType;
use App\Events\ApplicationStatusChanged;
use App\Models\User;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Listener for handling application status change notifications.
 * 
 * This listener is queued to prevent blocking the main request.
 * It implements idempotency to prevent duplicate notifications.
 */
class SendStatusChangeNotification implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * The queue connection that should handle the job.
     * Use 'sync' for immediate processing, 'database' for queued, or 'redis' if Redis is installed.
     */
    public string $connection = 'sync';

    /**
     * The queue name.
     */
    public string $queue = 'notifications';

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying.
     */
    public int $backoff = 60;

    /**
     * Handle the event.
     */
    public function handle(ApplicationStatusChanged $event): void
    {
        // Check idempotency - prevent duplicate notifications
        $idempotencyKey = $event->getIdempotencyKey();
        $cacheKey = 'notification_sent_' . $idempotencyKey;
        
        if (Cache::has($cacheKey)) {
            Log::info('Skipping duplicate notification', ['key' => $idempotencyKey]);
            return;
        }

        try {
            // Send notification to the applicant (user events)
            if ($event->eventType->isUserEvent()) {
                $this->notifyUser($event);
            }

            // Send notification to admins for relevant events
            if ($this->shouldNotifyAdmins($event->eventType)) {
                $this->notifyAdmins($event);
            }

            // Mark as sent (cache for 24 hours to prevent duplicates)
            Cache::put($cacheKey, true, now()->addHours(24));

            Log::info('Status change notification sent', [
                'application_id' => $event->application->id,
                'event_type' => $event->eventType->value,
                'old_status' => $event->oldStatus->value,
                'new_status' => $event->newStatus->value,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send status change notification', [
                'application_id' => $event->application->id,
                'event_type' => $event->eventType->value,
                'error' => $e->getMessage(),
            ]);
            
            throw $e; // Re-throw to trigger retry
        }
    }

    /**
     * Send notification to the applicant user
     */
    private function notifyUser(ApplicationStatusChanged $event): void
    {
        $user = $event->application->user;
        
        if (!$user) {
            Log::warning('Cannot notify user - user not found', [
                'application_id' => $event->application->id,
            ]);
            return;
        }

        $notification = new RegistrationStatusNotification(
            application: $event->application,
            eventType: $event->eventType,
            metadata: $event->metadata,
            isAdminNotification: false
        );

        $user->notify($notification);
    }

    /**
     * Send notification to all admin users
     */
    private function notifyAdmins(ApplicationStatusChanged $event): void
    {
        $admins = User::where('role', 'admin')->get();

        if ($admins->isEmpty()) {
            Log::warning('No admins found to notify');
            return;
        }

        foreach ($admins as $admin) {
            $notification = new RegistrationStatusNotification(
                application: $event->application,
                eventType: $event->eventType,
                metadata: $event->metadata,
                isAdminNotification: true
            );

            $admin->notify($notification);
        }
    }

    /**
     * Determine if admins should be notified for this event type
     */
    private function shouldNotifyAdmins(RegistrationEventType $eventType): bool
    {
        // Admin-specific events always notify admins
        if ($eventType->isAdminEvent()) {
            return true;
        }

        // Also notify admins for important status changes
        return in_array($eventType, [
            RegistrationEventType::APPLICATION_SUBMITTED,
            RegistrationEventType::APPLICATION_ACCEPTED,
            RegistrationEventType::APPLICATION_REJECTED,
        ]);
    }

    /**
     * Determine whether the listener should be queued.
     */
    public function shouldQueue(ApplicationStatusChanged $event): bool
    {
        // Always queue notification jobs
        return true;
    }

    /**
     * Handle a job failure.
     */
    public function failed(ApplicationStatusChanged $event, \Throwable $exception): void
    {
        Log::error('Status change notification failed permanently', [
            'application_id' => $event->application->id,
            'event_type' => $event->eventType->value,
            'error' => $exception->getMessage(),
        ]);
    }
}

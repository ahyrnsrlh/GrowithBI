<?php

namespace App\Listeners;

use App\Enums\RegistrationEventType;
use App\Events\DocumentsCompleted;
use App\Models\User;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

/**
 * Listener for handling document completion notifications (to admins).
 */
class SendDocumentsCompletedNotification implements ShouldQueue
{
    use InteractsWithQueue;

    public string $connection = 'sync';
    public string $queue = 'notifications';
    public int $tries = 3;
    public int $backoff = 60;

    /**
     * Handle the event.
     */
    public function handle(DocumentsCompleted $event): void
    {
        $idempotencyKey = $event->getIdempotencyKey();
        $cacheKey = 'notification_sent_' . $idempotencyKey;
        
        if (Cache::has($cacheKey)) {
            Log::info('Skipping duplicate documents completed notification', ['key' => $idempotencyKey]);
            return;
        }

        try {
            // Only notify admins for document completion
            $this->notifyAdmins($event);

            Cache::put($cacheKey, true, now()->addHours(24));

            Log::info('Documents completed notification sent', [
                'application_id' => $event->application->id,
                'user_id' => $event->user->id,
            ]);

        } catch (\Exception $e) {
            Log::error('Failed to send documents completed notification', [
                'application_id' => $event->application->id,
                'error' => $e->getMessage(),
            ]);
            
            throw $e;
        }
    }

    private function notifyAdmins(DocumentsCompleted $event): void
    {
        $admins = User::where('role', 'admin')->get();

        foreach ($admins as $admin) {
            $notification = new RegistrationStatusNotification(
                application: $event->application,
                eventType: RegistrationEventType::DOCUMENTS_COMPLETED,
                metadata: [
                    'completed_documents' => $event->completedDocuments,
                    'user_name' => $event->user->name,
                    'user_email' => $event->user->email,
                ],
                isAdminNotification: true
            );

            $admin->notify($notification);
        }
    }

    public function shouldQueue(DocumentsCompleted $event): bool
    {
        return true;
    }

    public function failed(DocumentsCompleted $event, \Throwable $exception): void
    {
        Log::error('Documents completed notification failed permanently', [
            'application_id' => $event->application->id,
            'error' => $exception->getMessage(),
        ]);
    }
}

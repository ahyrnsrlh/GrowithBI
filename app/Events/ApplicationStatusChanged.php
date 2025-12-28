<?php

namespace App\Events;

use App\Enums\RegistrationEventType;
use App\Enums\RegistrationStatus;
use App\Models\Application;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event fired when an application status changes.
 * 
 * This event is the central dispatcher for all registration-related notifications.
 * It handles both user and admin notifications based on the event type.
 */
class ApplicationStatusChanged implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public RegistrationEventType $eventType;
    public RegistrationStatus $oldStatus;
    public RegistrationStatus $newStatus;
    public ?User $changedBy;
    public array $metadata;

    /**
     * Create a new event instance.
     *
     * @param Application $application The application that changed
     * @param RegistrationEventType $eventType The type of event
     * @param RegistrationStatus $oldStatus Previous status
     * @param RegistrationStatus $newStatus New status
     * @param User|null $changedBy User who made the change (null for system changes)
     * @param array $metadata Additional data (interview_date, rejection_reason, etc.)
     */
    public function __construct(
        Application $application,
        RegistrationEventType $eventType,
        RegistrationStatus $oldStatus,
        RegistrationStatus $newStatus,
        ?User $changedBy = null,
        array $metadata = []
    ) {
        $this->application = $application->load(['user', 'division']);
        $this->eventType = $eventType;
        $this->oldStatus = $oldStatus;
        $this->newStatus = $newStatus;
        $this->changedBy = $changedBy;
        $this->metadata = $metadata;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        $channels = [];

        // Private channel for the applicant
        $channels[] = new PrivateChannel('user.' . $this->application->user_id);

        // Private channel for admins
        $channels[] = new PrivateChannel('admin.applications');

        return $channels;
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'application.status.changed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'application_id' => $this->application->id,
            'event_type' => $this->eventType->value,
            'old_status' => $this->oldStatus->value,
            'new_status' => $this->newStatus->value,
            'status_label' => $this->newStatus->label(),
            'status_color' => $this->newStatus->color(),
            'status_icon' => $this->newStatus->icon(),
            'user_id' => $this->application->user_id,
            'user_name' => $this->application->user->name,
            'division_id' => $this->application->division_id,
            'division_name' => $this->application->division->name,
            'title' => $this->eventType->title(false),
            'icon' => $this->eventType->icon(),
            'color' => $this->eventType->color(),
            'metadata' => $this->metadata,
            'changed_at' => now()->toISOString(),
            'changed_by' => $this->changedBy?->name,
        ];
    }

    /**
     * Determine if this event should be broadcast.
     */
    public function broadcastWhen(): bool
    {
        // Always broadcast status changes
        return true;
    }

    /**
     * Get a unique identifier for this event (for idempotency)
     */
    public function getIdempotencyKey(): string
    {
        return sprintf(
            'app_status_%d_%s_%s_%s',
            $this->application->id,
            $this->eventType->value,
            $this->oldStatus->value,
            $this->newStatus->value
        );
    }
}

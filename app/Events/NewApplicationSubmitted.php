<?php

namespace App\Events;

use App\Enums\RegistrationEventType;
use App\Models\Application;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event fired when a new application is submitted.
 * 
 * This triggers notifications to:
 * - The user (confirmation of submission)
 * - All admins (new application alert)
 */
class NewApplicationSubmitted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public array $metadata;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application, array $metadata = [])
    {
        $this->application = $application->load(['user', 'division']);
        $this->metadata = $metadata;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            // Notify the user
            new PrivateChannel('user.' . $this->application->user_id),
            // Notify all admins
            new PrivateChannel('admin.applications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'application.submitted';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'application_id' => $this->application->id,
            'event_type' => RegistrationEventType::APPLICATION_SUBMITTED->value,
            'user_id' => $this->application->user_id,
            'user_name' => $this->application->user->name,
            'user_email' => $this->application->user->email,
            'division_id' => $this->application->division_id,
            'division_name' => $this->application->division->name,
            'title' => RegistrationEventType::NEW_REGISTRATION->title(true),
            'icon' => RegistrationEventType::NEW_REGISTRATION->icon(),
            'color' => RegistrationEventType::NEW_REGISTRATION->color(),
            'submitted_at' => $this->application->created_at->toISOString(),
        ];
    }

    /**
     * Get a unique identifier for this event (for idempotency)
     */
    public function getIdempotencyKey(): string
    {
        return sprintf(
            'app_submitted_%d_%s',
            $this->application->id,
            $this->application->created_at->format('Y-m-d-H-i-s')
        );
    }
}

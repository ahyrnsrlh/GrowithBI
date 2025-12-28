<?php

namespace App\Events;

use App\Enums\RegistrationEventType;
use App\Models\Application;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Event fired when an acceptance letter is uploaded for an application.
 */
class AcceptanceLetterUploaded implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public User $uploadedBy;

    /**
     * Create a new event instance.
     */
    public function __construct(Application $application, User $uploadedBy)
    {
        $this->application = $application->load(['user', 'division']);
        $this->uploadedBy = $uploadedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('user.' . $this->application->user_id),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'application.letter.uploaded';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'application_id' => $this->application->id,
            'event_type' => RegistrationEventType::ACCEPTANCE_LETTER_READY->value,
            'user_id' => $this->application->user_id,
            'user_name' => $this->application->user->name,
            'division_name' => $this->application->division->name,
            'title' => RegistrationEventType::ACCEPTANCE_LETTER_READY->title(false),
            'icon' => RegistrationEventType::ACCEPTANCE_LETTER_READY->icon(),
            'color' => RegistrationEventType::ACCEPTANCE_LETTER_READY->color(),
            'download_url' => route('acceptance-letter.download', $this->application->id),
            'uploaded_at' => now()->toISOString(),
            'uploaded_by' => $this->uploadedBy->name,
        ];
    }

    /**
     * Get a unique identifier for this event (for idempotency)
     */
    public function getIdempotencyKey(): string
    {
        return sprintf(
            'letter_uploaded_%d_%s',
            $this->application->id,
            now()->format('Y-m-d-H-i-s')
        );
    }
}

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
 * Event fired when a user completes uploading all required documents.
 * 
 * This notifies admins that an application is ready for full review.
 */
class DocumentsCompleted implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Application $application;
    public User $user;
    public array $completedDocuments;

    /**
     * Create a new event instance.
     */
    public function __construct(
        Application $application, 
        User $user, 
        array $completedDocuments = []
    ) {
        $this->application = $application->load(['division']);
        $this->user = $user;
        $this->completedDocuments = $completedDocuments;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('admin.applications'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'application.documents.completed';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'application_id' => $this->application->id,
            'event_type' => RegistrationEventType::DOCUMENTS_COMPLETED->value,
            'user_id' => $this->user->id,
            'user_name' => $this->user->name,
            'user_email' => $this->user->email,
            'division_id' => $this->application->division_id,
            'division_name' => $this->application->division->name,
            'completed_documents' => $this->completedDocuments,
            'title' => RegistrationEventType::DOCUMENTS_COMPLETED->title(true),
            'icon' => RegistrationEventType::DOCUMENTS_COMPLETED->icon(),
            'color' => RegistrationEventType::DOCUMENTS_COMPLETED->color(),
            'completed_at' => now()->toISOString(),
        ];
    }

    /**
     * Get a unique identifier for this event (for idempotency)
     */
    public function getIdempotencyKey(): string
    {
        return sprintf(
            'docs_completed_%d_%s',
            $this->application->id,
            now()->format('Y-m-d')
        );
    }
}

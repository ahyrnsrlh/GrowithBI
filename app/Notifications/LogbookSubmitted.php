<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LogbookSubmitted extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    protected Logbook $logbook;

    /**
     * Create a new notification instance.
     */
    public function __construct(Logbook $logbook)
    {
        $this->logbook = $logbook;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $date = \Carbon\Carbon::parse($this->logbook->date)->format('d M Y');
        
        return [
            'type' => 'logbook_submitted',
            'title' => 'Logbook Baru Disubmit',
            'message' => $this->logbook->user->name . ' telah submit logbook tanggal ' . $date,
            'logbook_id' => $this->logbook->id,
            'user_id' => $this->logbook->user_id,
            'user_name' => $this->logbook->user->name,
            'date' => $this->logbook->date,
            'action_url' => '/admin/logbooks/' . $this->logbook->id,
            'icon' => 'document-text',
            'color' => 'blue',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): array
    {
        return ['private-App.User.' . $notifiable->id];
    }
}

<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LogbookStatusUpdated extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    protected Logbook $logbook;
    protected string $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Logbook $logbook, string $status)
    {
        $this->logbook = $logbook;
        $this->status = $status;
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
        
        $statusText = match($this->status) {
            'approved' => 'Disetujui',
            'revision' => 'Perlu Revisi',
            default => 'Pending'
        };
        
        $message = match($this->status) {
            'approved' => 'Logbook Anda tanggal ' . $date . ' telah disetujui',
            'revision' => 'Logbook Anda tanggal ' . $date . ' perlu direvisi',
            default => 'Status logbook Anda telah diupdate'
        };
        
        $color = match($this->status) {
            'approved' => 'green',
            'revision' => 'purple',
            default => 'orange'
        };
        
        $icon = match($this->status) {
            'approved' => 'check-circle',
            'revision' => 'arrow-path',
            default => 'clock'
        };
        
        return [
            'type' => 'logbook_status_updated',
            'title' => 'Status Logbook: ' . $statusText,
            'message' => $message,
            'logbook_id' => $this->logbook->id,
            'status' => $this->status,
            'date' => $this->logbook->date,
            'action_url' => '/profile/logbooks/' . $this->logbook->id,
            'icon' => $icon,
            'color' => $color,
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
        return ['private-App.User.' . $this->logbook->user_id];
    }
}

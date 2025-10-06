<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReportStatusUpdated extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    protected Report $report;
    protected string $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report, string $status)
    {
        $this->report = $report;
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
        $statusText = match($this->status) {
            'approved' => 'Disetujui',
            'revision' => 'Perlu Revisi',
            default => 'Submitted'
        };
        
        $message = match($this->status) {
            'approved' => 'Laporan Anda "' . $this->report->title . '" telah disetujui',
            'revision' => 'Laporan Anda "' . $this->report->title . '" perlu direvisi',
            default => 'Status laporan Anda telah diupdate'
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
            'type' => 'report_status_updated',
            'title' => 'Status Laporan: ' . $statusText,
            'message' => $message,
            'report_id' => $this->report->id,
            'status' => $this->status,
            'report_title' => $this->report->title,
            'action_url' => '/profile/reports/' . $this->report->id,
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
        return ['private-App.User.' . $this->report->user_id];
    }
}

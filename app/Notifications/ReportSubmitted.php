<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReportSubmitted extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    protected Report $report;

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report)
    {
        $this->report = $report;
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
        return [
            'type' => 'report_submitted',
            'title' => 'Laporan Baru Disubmit',
            'message' => $this->report->user->name . ' telah submit laporan akhir: ' . $this->report->title,
            'report_id' => $this->report->id,
            'user_id' => $this->report->user_id,
            'user_name' => $this->report->user->name,
            'title' => $this->report->title,
            'action_url' => '/admin/reports/' . $this->report->id,
            'icon' => 'document-chart-bar',
            'color' => 'indigo',
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

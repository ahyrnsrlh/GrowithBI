<?php

namespace App\Notifications;

use App\Models\Attendance;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class AttendanceCheckedOut extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    protected Attendance $attendance;

    /**
     * Create a new notification instance.
     */
    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance;
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
        $checkOutTime = \Carbon\Carbon::parse($this->attendance->check_out)->format('H:i');
        
        return [
            'type' => 'attendance_checked_out',
            'title' => 'Check-out Berhasil',
            'message' => 'Anda telah melakukan check-out pada ' . $checkOutTime,
            'attendance_id' => $this->attendance->id,
            'check_out_time' => $this->attendance->check_out,
            'date' => $this->attendance->date,
            'status' => $this->attendance->status,
            'action_url' => '/profile/attendance',
            'icon' => 'clock',
            'color' => 'amber',
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
        return ['private-App.User.' . $this->attendance->user_id];
    }
}

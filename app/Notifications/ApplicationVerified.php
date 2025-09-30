<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationVerified extends Notification implements ShouldBroadcast, ShouldQueue
{
    use Queueable;

    protected Application $application;
    protected string $status;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application, string $status)
    {
        $this->application = $application;
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
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $statusText = $this->status === 'verified' ? 'diverifikasi' : 'ditolak';
        
        return (new MailMessage)
                    ->line('Pendaftaran Anda telah ' . $statusText . '.')
                    ->action('Lihat Detail', url('/peserta/applications/' . $this->application->id))
                    ->line('Terima kasih telah menggunakan aplikasi kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $statusText = $this->status === 'verified' ? 'Diverifikasi' : 'Ditolak';
        $message = $this->status === 'verified' 
            ? 'Selamat! Pendaftaran Anda telah diverifikasi' 
            : 'Mohon maaf, pendaftaran Anda ditolak';
        
        return [
            'type' => 'application_verified',
            'title' => 'Status Pendaftaran: ' . $statusText,
            'message' => $message,
            'application_id' => $this->application->id,
            'status' => $this->status,
            'division_name' => $this->application->division->name,
            'action_url' => '/peserta/applications/' . $this->application->id,
            'icon' => $this->status === 'verified' ? 'check-circle' : 'x-circle',
            'color' => $this->status === 'verified' ? 'green' : 'red',
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
        return ['private-App.User.' . $this->application->user->id];
    }
}

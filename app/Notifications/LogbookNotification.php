<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class LogbookNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $logbook;
    public $type; // 'submitted', 'approved', 'rejected', 'commented', 'reminder'

    /**
     * Create a new notification instance.
     */
    public function __construct(Logbook $logbook, string $type)
    {
        $this->logbook = $logbook;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Email hanya untuk approved dan rejected
        $channels = ['database', 'broadcast'];
        
        if (in_array($this->type, ['approved', 'rejected'])) {
            $channels[] = 'mail';
        }
        
        return $channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject($this->getTitle());

        switch ($this->type) {
            case 'approved':
                return $message
                    ->greeting('Logbook Disetujui')
                    ->line('Logbook Anda untuk tanggal ' . $this->logbook->date->format('d F Y') . ' telah disetujui oleh supervisor.')
                    ->line('Aktivitas: ' . $this->logbook->activity)
                    ->action('Lihat Detail', url('/peserta/logbook/' . $this->logbook->id));

            case 'rejected':
                return $message
                    ->greeting('Logbook Perlu Revisi')
                    ->line('Logbook Anda untuk tanggal ' . $this->logbook->date->format('d F Y') . ' perlu direvisi.')
                    ->line('Catatan: ' . ($this->logbook->supervisor_notes ?? 'Tidak ada catatan'))
                    ->action('Revisi Logbook', url('/peserta/logbook/' . $this->logbook->id));

            default:
                return $message->line('Status logbook Anda telah diperbarui.');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => $this->type,
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'logbook_id' => $this->logbook->id,
            'date' => $this->logbook->date->format('Y-m-d'),
            'activity' => $this->logbook->activity,
            'status' => $this->logbook->status,
            'url' => '/peserta/logbook/' . $this->logbook->id,
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'type' => $this->type,
            'title' => $this->getTitle(),
            'message' => $this->getMessage(),
            'logbook_id' => $this->logbook->id,
            'date' => $this->logbook->date->format('Y-m-d'),
            'activity' => $this->logbook->activity,
            'status' => $this->logbook->status,
            'url' => '/peserta/logbook/' . $this->logbook->id,
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'created_at' => now()->toISOString(),
        ]);
    }

    /**
     * Get notification title based on type
     */
    private function getTitle(): string
    {
        return match($this->type) {
            'submitted' => 'Logbook Berhasil Dikirim',
            'approved' => 'Logbook Disetujui',
            'rejected' => 'Logbook Perlu Revisi',
            'commented' => 'Komentar Baru di Logbook',
            'reminder' => 'Reminder Pengisian Logbook',
            default => 'Notifikasi Logbook',
        };
    }

    /**
     * Get notification message based on type
     */
    private function getMessage(): string
    {
        return match($this->type) {
            'submitted' => 'Logbook Anda untuk tanggal ' . $this->logbook->date->format('d/m/Y') . ' telah berhasil dikirim.',
            'approved' => 'Logbook Anda untuk tanggal ' . $this->logbook->date->format('d/m/Y') . ' telah disetujui.',
            'rejected' => 'Logbook Anda untuk tanggal ' . $this->logbook->date->format('d/m/Y') . ' perlu direvisi.',
            'commented' => 'Supervisor memberikan komentar pada logbook Anda tanggal ' . $this->logbook->date->format('d/m/Y') . '.',
            'reminder' => 'Jangan lupa mengisi logbook untuk hari ini.',
            default => 'Logbook Anda telah diperbarui.',
        };
    }

    /**
     * Get icon based on type
     */
    private function getIcon(): string
    {
        return match($this->type) {
            'submitted' => 'document-check',
            'approved' => 'check-circle',
            'rejected' => 'x-circle',
            'commented' => 'chat-bubble-left-right',
            'reminder' => 'bell-alert',
            default => 'book-open',
        };
    }

    /**
     * Get color based on type
     */
    private function getColor(): string
    {
        return match($this->type) {
            'submitted' => 'blue',
            'approved' => 'green',
            'rejected' => 'red',
            'commented' => 'purple',
            'reminder' => 'yellow',
            default => 'gray',
        };
    }
}

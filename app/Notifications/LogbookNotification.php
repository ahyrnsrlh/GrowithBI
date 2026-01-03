<?php

namespace App\Notifications;

use App\Models\Logbook;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Support\Facades\Auth;

class LogbookNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Logbook $logbook;
    public string $type; // 'submitted', 'approved', 'rejected', 'revision_requested', 'commented', 'reminder'
    public ?int $senderId;
    public ?string $receiverRole;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 10;

    /**
     * Create a new notification instance.
     */
    public function __construct(Logbook $logbook, string $type, ?int $senderId = null, ?string $receiverRole = null)
    {
        $this->logbook = $logbook;
        $this->type = $type;
        $this->senderId = $senderId ?? (Auth::check() ? Auth::id() : null);
        $this->receiverRole = $receiverRole;
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
     * Get the channels the event should broadcast on.
     * This ensures the notification is sent to the correct private channel.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('App.Models.User.' . $this->logbook->user_id),
        ];
    }

    /**
     * Get the type of the notification being broadcast.
     * This determines the event name on the frontend.
     */
    public function broadcastType(): string
    {
        return 'logbook.notification';
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
            'message' => $this->getMessage($notifiable),
            'logbook_id' => $this->logbook->id,
            'user_name' => $this->logbook->user->name,
            'date' => $this->logbook->date->format('Y-m-d'),
            'activity' => $this->logbook->activities ?? $this->logbook->activity ?? '',
            'status' => $this->logbook->status,
            'url' => $this->getUrl($notifiable),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'sender_id' => $this->senderId,
            'receiver_role' => $this->receiverRole ?? $notifiable->role ?? 'user',
        ];
    }

    /**
     * Get the broadcastable representation of the notification.
     * Enhanced payload for real-time updates.
     */
    public function toBroadcast(object $notifiable): BroadcastMessage
    {
        return new BroadcastMessage([
            'id' => $this->id ?? uniqid('logbook_'),
            'type' => $this->type,
            'notification_type' => 'logbook',
            'title' => $this->getTitle(),
            'message' => $this->getMessage($notifiable),
            'logbook_id' => $this->logbook->id,
            'user_id' => $this->logbook->user_id,
            'user_name' => $this->logbook->user->name ?? 'Unknown',
            'date' => $this->logbook->date->format('Y-m-d'),
            'date_formatted' => $this->logbook->date->format('d/m/Y'),
            'activity' => $this->logbook->activities ?? $this->logbook->activity ?? '',
            'status' => $this->logbook->status,
            'url' => $this->getUrl($notifiable),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'sender_id' => $this->senderId,
            'receiver_role' => $this->receiverRole ?? $notifiable->role ?? 'user',
            'created_at' => now()->toISOString(),
            'read_at' => null,
        ]);
    }

    /**
     * Get the appropriate URL based on user role
     */
    private function getUrl(object $notifiable): string
    {
        $isAdmin = $notifiable->role === 'admin' || $notifiable->role === 'mentor';
        
        if ($isAdmin) {
            return '/admin/logbooks/' . $this->logbook->id;
        }
        
        return '/peserta/logbook/' . $this->logbook->id;
    }

    /**
     * Get notification title based on type
     */
    private function getTitle(): string
    {
        return match($this->type) {
            'submitted' => 'Logbook Baru Dikirim',
            'approved' => 'Logbook Disetujui',
            'rejected' => 'Logbook Ditolak',
            'revision_requested' => 'Permintaan Revisi Logbook',
            'commented' => 'Komentar Baru di Logbook',
            'reminder' => 'Reminder Pengisian Logbook',
            'pending_over_3_days' => 'Logbook Pending > 3 Hari',
            'not_submitted_today' => 'User Belum Submit Logbook',
            default => 'Notifikasi Logbook',
        };
    }

    /**
     * Get notification message based on type
     * Berbeda untuk admin (menampilkan nama user) vs user (menampilkan "Anda")
     */
    private function getMessage(object $notifiable): string
    {
        $userName = $this->logbook->user->name ?? 'Peserta';
        $isAdmin = in_array($notifiable->role ?? '', ['admin', 'mentor']);
        $dateFormatted = $this->logbook->date->format('d/m/Y');
        
        return match($this->type) {
            'submitted' => $isAdmin
                ? "{$userName} mengirim logbook tanggal {$dateFormatted}"
                : "Logbook Anda untuk tanggal {$dateFormatted} berhasil dikirim.",
            'approved' => $isAdmin
                ? "Logbook {$userName} tanggal {$dateFormatted} telah disetujui"
                : "Logbook Anda untuk tanggal {$dateFormatted} telah disetujui.",
            'rejected' => $isAdmin
                ? "Logbook {$userName} tanggal {$dateFormatted} ditolak"
                : "Logbook Anda untuk tanggal {$dateFormatted} ditolak.",
            'revision_requested' => $isAdmin
                ? "Permintaan revisi dikirim ke {$userName} untuk logbook tanggal {$dateFormatted}"
                : "Logbook Anda untuk tanggal {$dateFormatted} perlu direvisi.",
            'commented' => $isAdmin
                ? "Komentar baru pada logbook {$userName} tanggal {$dateFormatted}"
                : "Supervisor memberikan komentar pada logbook Anda tanggal {$dateFormatted}.",
            'reminder' => $isAdmin
                ? "{$userName} belum mengisi logbook hari ini"
                : "Jangan lupa mengisi logbook untuk hari ini.",
            'pending_over_3_days' => "Logbook {$userName} tanggal {$dateFormatted} pending lebih dari 3 hari.",
            'not_submitted_today' => "{$userName} belum submit logbook hari ini.",
            default => $isAdmin
                ? "Logbook {$userName} telah diperbarui"
                : "Logbook Anda telah diperbarui.",
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
            'revision_requested' => 'arrow-path',
            'commented' => 'chat-bubble-left-right',
            'reminder' => 'bell-alert',
            'pending_over_3_days' => 'clock',
            'not_submitted_today' => 'exclamation-triangle',
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
            'revision_requested' => 'orange',
            'commented' => 'purple',
            'reminder' => 'yellow',
            'pending_over_3_days' => 'amber',
            'not_submitted_today' => 'red',
            default => 'gray',
        };
    }
}

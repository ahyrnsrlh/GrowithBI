<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class RegistrationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $application;
    public $type; // 'submitted', 'verified', 'accepted', 'rejected', 'letter_sent', 'application_expired'

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application, string $type)
    {
        $this->application = $application;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Database + Broadcast + Mail untuk semua event registrasi
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $message = (new MailMessage)
            ->subject($this->getTitle());

        switch ($this->type) {
            case 'submitted':
                return $message
                    ->greeting('Pendaftaran Berhasil Dikirim')
                    ->line('Pendaftaran Anda untuk posisi ' . $this->application->division->name . ' telah berhasil dikirim.')
                    ->line('Tim kami akan segera memverifikasi dokumen Anda.')
                    ->action('Lihat Status Pendaftaran', url('/peserta/application'));
                
            case 'verified':
                return $message
                    ->greeting('Dokumen Terverifikasi')
                    ->line('Dokumen pendaftaran Anda telah diverifikasi oleh admin.')
                    ->line('Anda dapat melanjutkan ke tahap selanjutnya.')
                    ->action('Lihat Detail', url('/peserta/application'));

            case 'accepted':
                return $message
                    ->greeting('Selamat! Pendaftaran Diterima')
                    ->line('Anda telah diterima untuk posisi ' . $this->application->division->name . '.')
                    ->line('Silakan cek dashboard Anda untuk informasi lebih lanjut.')
                    ->action('Lihat Detail', url('/peserta/application'));

            case 'rejected':
                return $message
                    ->greeting('Pendaftaran Ditolak')
                    ->line('Mohon maaf, pendaftaran Anda untuk posisi ' . $this->application->division->name . ' tidak dapat kami terima.')
                    ->line('Alasan: ' . ($this->application->rejection_reason ?? 'Tidak disebutkan'))
                    ->line('Anda dapat mencoba mendaftar kembali di periode berikutnya.');

            case 'letter_sent':
                return $message
                    ->greeting('Surat Penerimaan Tersedia')
                    ->line('Surat penerimaan resmi Anda telah tersedia untuk diunduh.')
                    ->action('Download Surat', url('/peserta/application'))
                    ->line('Harap cetak dan bawa surat ini pada hari pertama magang.');

            case 'application_expired':
                return $message
                    ->greeting('Pendaftaran Kedaluwarsa')
                    ->line('Pendaftaran Anda telah melewati batas waktu tanpa tindakan lanjutan.')
                    ->line('Silakan hubungi admin jika ada pertanyaan.');

            default:
                return $message->line('Status pendaftaran Anda telah diperbarui.');
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
            'application_id' => $this->application->id,
            'user_name' => $this->application->user->name,
            'division_name' => $this->application->division->name,
            'status' => $this->application->status,
            'url' => '/peserta/application',
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
            'message' => $this->getMessage($notifiable),
            'application_id' => $this->application->id,
            'user_name' => $this->application->user->name,
            'division_name' => $this->application->division->name,
            'status' => $this->application->status,
            'url' => '/peserta/application',
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
            'submitted' => 'Pendaftaran Berhasil Dikirim',
            'verified' => 'Dokumen Terverifikasi',
            'accepted' => 'Pendaftaran Diterima',
            'rejected' => 'Pendaftaran Ditolak',
            'letter_sent' => 'Surat Penerimaan Tersedia',
            'application_expired' => 'Pendaftaran Kedaluwarsa',
            default => 'Status Pendaftaran Diperbarui',
        };
    }

    /**
     * Get notification message based on type
     * Berbeda untuk admin (menampilkan nama user) vs user (menampilkan "Anda")
     */
    private function getMessage(object $notifiable): string
    {
        $userName = $this->application->user->name;
        $isAdmin = $notifiable->role === 'admin';
        $divisionName = $this->application->division->name;
        
        return match($this->type) {
            'submitted' => $isAdmin
                ? "{$userName} mendaftar untuk posisi {$divisionName}"
                : "Pendaftaran Anda untuk posisi {$divisionName} telah berhasil dikirim.",
            'verified' => $isAdmin
                ? "Dokumen pendaftaran {$userName} telah diverifikasi"
                : "Dokumen pendaftaran Anda telah diverifikasi oleh admin.",
            'accepted' => $isAdmin
                ? "{$userName} telah diterima untuk posisi {$divisionName}"
                : "Selamat! Anda telah diterima untuk posisi {$divisionName}.",
            'rejected' => $isAdmin
                ? "{$userName} tidak diterima untuk posisi {$divisionName}"
                : "Mohon maaf, pendaftaran Anda untuk posisi {$divisionName} tidak dapat kami terima.",
            'letter_sent' => $isAdmin
                ? "Surat penerimaan {$userName} telah dikirim"
                : "Surat penerimaan resmi Anda telah tersedia untuk diunduh.",
            'application_expired' => $isAdmin
                ? "Pendaftaran {$userName} telah kedaluwarsa"
                : "Pendaftaran Anda telah melewati batas waktu tanpa tindakan lanjutan.",
            default => $isAdmin
                ? "Status pendaftaran {$userName} telah diperbarui"
                : "Status pendaftaran Anda telah diperbarui.",
        };
    }

    /**
     * Get icon based on type
     */
    private function getIcon(): string
    {
        return match($this->type) {
            'submitted' => 'paper-airplane',
            'verified' => 'check-circle',
            'accepted' => 'check-badge',
            'rejected' => 'x-circle',
            'letter_sent' => 'document-text',
            'application_expired' => 'clock',
            default => 'bell',
        };
    }

    /**
     * Get color based on type
     */
    private function getColor(): string
    {
        return match($this->type) {
            'submitted' => 'blue',
            'verified' => 'indigo',
            'accepted' => 'green',
            'rejected' => 'red',
            'letter_sent' => 'purple',
            'application_expired' => 'gray',
            default => 'gray',
        };
    }
}

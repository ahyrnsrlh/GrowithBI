<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReportNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $report;
    public $type; // 'submitted', 'reviewed', 'approved', 'revision_requested', 'deadline_reminder', 'deadline_passed', 'certificate_ready'

    /**
     * Create a new notification instance.
     */
    public function __construct(Report $report, string $type)
    {
        $this->report = $report;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        // Email untuk event penting: approved, revision_requested, deadline_passed, certificate_ready
        $channels = ['database', 'broadcast'];
        
        if (in_array($this->type, ['approved', 'revision_requested', 'deadline_passed', 'certificate_ready'])) {
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
                    ->greeting('Laporan Akhir Disetujui')
                    ->line('Selamat! Laporan akhir Anda telah disetujui oleh supervisor.')
                    ->line('Anda telah menyelesaikan program magang dengan baik.')
                    ->action('Lihat Laporan', url('/peserta/report'))
                    ->line('Sertifikat Anda akan segera diproses.');

            case 'revision_requested':
                return $message
                    ->greeting('Laporan Perlu Revisi')
                    ->line('Laporan akhir Anda memerlukan beberapa revisi.')
                    ->line('Catatan: ' . ($this->report->feedback ?? 'Tidak ada catatan spesifik'))
                    ->action('Revisi Laporan', url('/peserta/report'))
                    ->line('Harap lakukan revisi secepatnya.');

            case 'deadline_passed':
                return $message
                    ->greeting('Deadline Laporan Terlewati')
                    ->line('Anda telah melewati deadline pengumpulan laporan akhir.')
                    ->line('Mohon segera hubungi supervisor Anda untuk tindakan lebih lanjut.')
                    ->action('Lihat Laporan', url('/peserta/report'));

            case 'certificate_ready':
                return $message
                    ->greeting('Sertifikat Magang Tersedia')
                    ->line('Sertifikat magang Anda telah siap untuk diunduh.')
                    ->line('Terima kasih telah menyelesaikan program magang dengan baik.')
                    ->action('Download Sertifikat', url('/peserta/report'));

            default:
                return $message->line('Status laporan Anda telah diperbarui.');
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
            'report_id' => $this->report->id,
            'user_name' => $this->report->user->name,
            'status' => $this->report->status,
            'url' => '/peserta/report',
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
            'report_id' => $this->report->id,
            'user_name' => $this->report->user->name,
            'status' => $this->report->status,
            'url' => '/peserta/report',
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
            'submitted' => 'Laporan Akhir Berhasil Dikirim',
            'reviewed' => 'Laporan Sedang Direview',
            'approved' => 'Laporan Akhir Disetujui',
            'revision_requested' => 'Laporan Perlu Revisi',
            'graded' => 'Nilai Laporan Tersedia',
            'deadline_reminder' => 'Reminder Deadline Laporan',
            'deadline_passed' => 'Deadline Laporan Terlewati',
            'overdue' => 'Laporan Overdue',
            'certificate_ready' => 'Sertifikat Magang Tersedia',
            default => 'Notifikasi Laporan',
        };
    }

    /**
     * Get notification message based on type
     * Berbeda untuk admin (menampilkan nama user) vs user (menampilkan "Anda")
     */
    private function getMessage(object $notifiable): string
    {
        $userName = $this->report->user->name;
        $isAdmin = $notifiable->role === 'admin';
        
        return match($this->type) {
            'submitted' => $isAdmin
                ? "{$userName} telah mengirim laporan akhir"
                : "Laporan akhir Anda telah berhasil dikirim dan akan segera direview oleh supervisor.",
            'reviewed' => $isAdmin
                ? "Laporan akhir {$userName} sedang direview"
                : "Supervisor sedang mereview laporan akhir Anda.",
            'approved' => $isAdmin
                ? "Laporan akhir {$userName} telah disetujui"
                : "Selamat! Laporan akhir Anda telah disetujui. Sertifikat Anda akan segera diproses.",
            'revision_requested' => $isAdmin
                ? "Laporan akhir {$userName} memerlukan revisi"
                : "Laporan akhir Anda memerlukan revisi. Silakan cek catatan dari supervisor.",
            'graded' => $isAdmin
                ? "Nilai laporan akhir {$userName} telah diinput"
                : "Nilai laporan akhir Anda telah tersedia. Silakan cek di dashboard.",
            'deadline_reminder' => $isAdmin
                ? "Deadline laporan akhir {$userName} akan berakhir dalam 3 hari"
                : "Deadline pengumpulan laporan akhir akan berakhir dalam 3 hari. Segera kirimkan laporan Anda.",
            'deadline_passed' => $isAdmin
                ? "{$userName} melewati deadline laporan akhir"
                : "Anda telah melewati deadline pengumpulan laporan akhir. Segera hubungi supervisor.",
            'overdue' => $isAdmin
                ? "Laporan akhir {$userName} sudah overdue dan perlu segera dikumpulkan"
                : "Laporan akhir Anda sudah melewati deadline! Segera selesaikan dan kirimkan.",
            'certificate_ready' => $isAdmin
                ? "Sertifikat magang {$userName} telah siap"
                : "Sertifikat magang Anda telah siap untuk diunduh. Terima kasih atas partisipasi Anda.",
            default => $isAdmin
                ? "Status laporan {$userName} telah diperbarui"
                : "Status laporan Anda telah diperbarui.",
        };
    }

    /**
     * Get icon based on type
     */
    private function getIcon(): string
    {
        return match($this->type) {
            'submitted' => 'document-arrow-up',
            'reviewed' => 'magnifying-glass',
            'approved' => 'check-badge',
            'revision_requested' => 'arrow-path',
            'graded' => 'star',
            'deadline_reminder' => 'clock',
            'deadline_passed' => 'exclamation-triangle',
            'overdue' => 'exclamation-circle',
            'certificate_ready' => 'academic-cap',
            default => 'document-text',
        };
    }

    /**
     * Get color based on type
     */
    private function getColor(): string
    {
        return match($this->type) {
            'submitted' => 'blue',
            'reviewed' => 'indigo',
            'approved' => 'green',
            'revision_requested' => 'orange',
            'graded' => 'purple',
            'deadline_reminder' => 'yellow',
            'deadline_passed' => 'red',
            'overdue' => 'red',
            'certificate_ready' => 'purple',
            default => 'gray',
        };
    }
}

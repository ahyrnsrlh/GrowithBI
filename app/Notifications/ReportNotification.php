<?php

namespace App\Notifications;

use App\Models\Report;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Notification;

class ReportNotification extends Notification implements ShouldBroadcast
{
    use Queueable;

    public $report;
    public $type; // 'submitted', 'under_review', 'approved', 'revision_required', 'deadline_approaching', 'overdue', 'resubmitted'

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
    public function __construct(Report $report, string $type)
    {
        $this->report = $report;
        $this->type = $type;
    }

    /**
     * Get the notification's delivery channels.
     * Only database + broadcast (NO email per requirements)
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
            'type' => $this->type,
            'title' => $this->getTitle(),
            'message' => $this->getMessage($notifiable),
            'report_id' => $this->report->id,
            'user_id' => $this->report->user_id,
            'user_name' => $this->report->user->name ?? 'Unknown',
            'status' => $this->report->status,
            'url' => $this->getUrl($notifiable),
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
            'id' => $this->id ?? uniqid('report_'),
            'type' => $this->type,
            'notification_type' => 'report',
            'title' => $this->getTitle(),
            'message' => $this->getMessage($notifiable),
            'report_id' => $this->report->id,
            'user_id' => $this->report->user_id,
            'user_name' => $this->report->user->name ?? 'Unknown',
            'status' => $this->report->status,
            'url' => $this->getUrl($notifiable),
            'icon' => $this->getIcon(),
            'color' => $this->getColor(),
            'created_at' => now()->toISOString(),
            'read_at' => null,
        ]);
    }

    /**
     * Get the appropriate URL based on user role
     */
    private function getUrl(object $notifiable): string
    {
        $isAdmin = in_array($notifiable->role ?? '', ['admin', 'mentor']);
        
        if ($isAdmin) {
            return '/admin/reports/' . $this->report->id;
        }
        
        return '/peserta/report';
    }

    /**
     * Get notification title based on type
     */
    private function getTitle(): string
    {
        return match($this->type) {
            'submitted' => 'Laporan Akhir Dikirim',
            'under_review' => 'Laporan Sedang Direview',
            'approved' => 'Laporan Akhir Disetujui',
            'revision_required' => 'Laporan Perlu Revisi',
            'deadline_approaching' => 'Deadline Laporan Mendekat',
            'overdue' => 'Laporan Overdue',
            'resubmitted' => 'Laporan Direvisi',
            default => 'Notifikasi Laporan',
        };
    }

    /**
     * Get notification message based on type
     * Berbeda untuk admin (menampilkan nama user) vs user (menampilkan "Anda")
     */
    private function getMessage(object $notifiable): string
    {
        $userName = $this->report->user->name ?? 'Peserta';
        $isAdmin = in_array($notifiable->role ?? '', ['admin', 'mentor']);
        
        return match($this->type) {
            'submitted' => $isAdmin
                ? "{$userName} telah mengirim laporan akhir"
                : "Laporan akhir Anda telah berhasil dikirim.",
            'under_review' => $isAdmin
                ? "Laporan akhir {$userName} sedang direview"
                : "Supervisor sedang mereview laporan akhir Anda.",
            'approved' => $isAdmin
                ? "Laporan akhir {$userName} telah disetujui"
                : "Selamat! Laporan akhir Anda telah disetujui.",
            'revision_required' => $isAdmin
                ? "Laporan akhir {$userName} memerlukan revisi"
                : "Laporan akhir Anda memerlukan revisi. Silakan cek catatan dari supervisor.",
            'deadline_approaching' => $isAdmin
                ? "Deadline laporan akhir {$userName} akan berakhir dalam 3 hari"
                : "Deadline pengumpulan laporan akhir akan berakhir dalam 3 hari.",
            'overdue' => $isAdmin
                ? "Laporan akhir {$userName} sudah melewati deadline"
                : "Laporan akhir Anda sudah melewati deadline! Segera selesaikan.",
            'resubmitted' => $isAdmin
                ? "{$userName} telah mengupload revisi laporan akhir"
                : "Revisi laporan akhir Anda telah berhasil dikirim.",
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
            'under_review' => 'magnifying-glass',
            'approved' => 'check-badge',
            'revision_required' => 'arrow-path',
            'deadline_approaching' => 'clock',
            'overdue' => 'exclamation-circle',
            'resubmitted' => 'document-arrow-up',
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
            'under_review' => 'indigo',
            'approved' => 'green',
            'revision_required' => 'orange',
            'deadline_approaching' => 'yellow',
            'overdue' => 'red',
            'resubmitted' => 'blue',
            default => 'gray',
        };
    }
}

<?php

namespace App\Notifications;

use App\Enums\RegistrationEventType;
use App\Enums\RegistrationStatus;
use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Unified notification class for all registration-related events.
 * 
 * This notification supports:
 * - Multiple event types via RegistrationEventType enum
 * - Role-aware content (different messages for users vs admins)
 * - Multiple channels: database, broadcast, and email
 * - Clean payload structure with metadata support
 */
class RegistrationStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public Application $application;
    public RegistrationEventType $eventType;
    public array $metadata;
    public bool $isAdminNotification;

    /**
     * Create a new notification instance.
     */
    public function __construct(
        Application $application,
        RegistrationEventType $eventType,
        array $metadata = [],
        bool $isAdminNotification = false
    ) {
        $this->application = $application;
        $this->eventType = $eventType;
        $this->metadata = $metadata;
        $this->isAdminNotification = $isAdminNotification;
        
        // Configure queue settings using Queueable trait methods
        $this->onConnection('sync'); // Use sync for immediate processing
        $this->onQueue('notifications');
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'broadcast', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $divisionName = $this->application->division->name ?? 'N/A';
        $userName = $this->application->user->name ?? 'Pendaftar';

        $message = (new MailMessage)
            ->subject($this->eventType->emailSubject($divisionName));

        return match($this->eventType) {
            RegistrationEventType::APPLICATION_SUBMITTED => $message
                ->greeting('Halo ' . $notifiable->name . '!')
                ->line('Pendaftaran Anda untuk posisi **' . $divisionName . '** telah berhasil dikirim.')
                ->line('Tim kami akan segera meninjau berkas Anda.')
                ->line('Anda akan menerima notifikasi ketika ada update status pendaftaran.')
                ->action('Lihat Status Pendaftaran', url('/profile'))
                ->line('Terima kasih telah mendaftar di GrowithBI!'),

            RegistrationEventType::SELECTION_IN_PROGRESS => $message
                ->greeting('Halo ' . $notifiable->name . '!')
                ->line('Proses seleksi untuk pendaftaran Anda di posisi **' . $divisionName . '** telah dimulai.')
                ->line('Tim kami sedang meninjau kelengkapan dokumen dan kualifikasi Anda.')
                ->action('Lihat Detail', url('/profile'))
                ->line('Mohon tunggu informasi selanjutnya.'),

            RegistrationEventType::INTERVIEW_SCHEDULED => $message
                ->greeting('Halo ' . $notifiable->name . '!')
                ->line('Selamat! Anda telah lolos tahap seleksi dokumen.')
                ->line('Jadwal wawancara Anda telah ditentukan:')
                ->line('**Tanggal:** ' . ($this->metadata['interview_date'] ?? 'Akan dikonfirmasi'))
                ->line('**Jam:** ' . ($this->metadata['interview_time'] ?? 'Akan dikonfirmasi'))
                ->line('**Lokasi:** ' . ($this->metadata['interview_location'] ?? 'Akan dikonfirmasi'))
                ->when(
                    isset($this->metadata['interview_location_detail']),
                    fn ($m) => $m->line('**Detail Lokasi:** ' . $this->metadata['interview_location_detail'])
                )
                ->action('Lihat Detail Wawancara', url('/profile'))
                ->line('Silakan hadir tepat waktu dan persiapkan diri dengan baik.'),

            RegistrationEventType::INTERVIEW_RESCHEDULED => $message
                ->greeting('Halo ' . $notifiable->name . '!')
                ->line('Jadwal wawancara Anda telah diubah.')
                ->line('**Jadwal Baru:**')
                ->line('**Tanggal:** ' . ($this->metadata['interview_date'] ?? 'Akan dikonfirmasi'))
                ->line('**Jam:** ' . ($this->metadata['interview_time'] ?? 'Akan dikonfirmasi'))
                ->line('**Lokasi:** ' . ($this->metadata['interview_location'] ?? 'Akan dikonfirmasi'))
                ->when(
                    isset($this->metadata['interview_location_detail']),
                    fn ($m) => $m->line('**Detail Lokasi:** ' . $this->metadata['interview_location_detail'])
                )
                ->action('Lihat Detail', url('/profile'))
                ->line('Mohon maaf atas ketidaknyamanan ini.'),

            RegistrationEventType::APPLICATION_ACCEPTED => $message
                ->greeting('Selamat ' . $notifiable->name . '! 🎉')
                ->line('Kami dengan senang hati mengumumkan bahwa Anda telah **DITERIMA** sebagai peserta magang di posisi **' . $divisionName . '**.')
                ->line('Surat penerimaan resmi akan segera tersedia untuk diunduh di dashboard Anda.')
                ->action('Lihat Dashboard', url('/profile'))
                ->line('Selamat bergabung dengan tim GrowithBI!'),

            RegistrationEventType::APPLICATION_REJECTED => $message
                ->greeting('Halo ' . $notifiable->name)
                ->line('Kami informasikan bahwa pendaftaran Anda untuk posisi **' . $divisionName . '** tidak dapat kami terima pada periode ini.')
                ->when(
                    isset($this->metadata['rejection_reason']),
                    fn ($m) => $m->line('**Alasan:** ' . $this->metadata['rejection_reason'])
                )
                ->line('Jangan berkecil hati! Anda dapat mencoba mendaftar kembali di periode berikutnya.')
                ->line('Terima kasih atas minat Anda di GrowithBI.'),

            RegistrationEventType::ACCEPTANCE_LETTER_READY => $message
                ->greeting('Halo ' . $notifiable->name . '!')
                ->line('Surat penerimaan resmi Anda telah tersedia untuk diunduh.')
                ->line('Silakan unduh dan cetak surat ini untuk dibawa pada hari pertama magang.')
                ->action('Download Surat Penerimaan', $this->metadata['download_url'] ?? url('/profile'))
                ->line('Pastikan untuk menyimpan salinan digital dan cetak dari surat ini.'),

            RegistrationEventType::APPLICATION_EXPIRED => $message
                ->greeting('Halo ' . $notifiable->name)
                ->line('Pendaftaran Anda untuk posisi **' . $divisionName . '** telah melewati batas waktu yang ditentukan.')
                ->line('Jika Anda masih berminat, silakan hubungi tim kami atau ajukan pendaftaran baru.')
                ->action('Hubungi Kami', url('/contact')),

            // Admin-only notifications
            RegistrationEventType::NEW_REGISTRATION => $message
                ->greeting('Pendaftaran Baru!')
                ->line('**' . $userName . '** telah mendaftar untuk posisi **' . $divisionName . '**.')
                ->line('**Email:** ' . ($this->application->user->email ?? 'N/A'))
                ->action('Review Pendaftaran', url('/admin/applications/' . $this->application->id))
                ->line('Silakan tinjau dokumen pendaftaran.'),

            RegistrationEventType::DOCUMENTS_COMPLETED => $message
                ->greeting('Dokumen Lengkap!')
                ->line('**' . $userName . '** telah melengkapi semua dokumen yang diperlukan.')
                ->line('Posisi yang dilamar: **' . $divisionName . '**')
                ->action('Review Dokumen', url('/admin/applications/' . $this->application->id))
                ->line('Pendaftaran siap untuk ditinjau lebih lanjut.'),

            default => $message
                ->greeting('Halo ' . $notifiable->name)
                ->line('Status pendaftaran Anda telah diperbarui.')
                ->action('Lihat Detail', url('/profile')),
        };
    }

    /**
     * Get the array representation of the notification (for database channel).
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        $divisionName = $this->application->division->name ?? 'N/A';
        $userName = $this->application->user->name ?? 'Pendaftar';

        return [
            // Core data
            'type' => 'registration_status',
            'event_type' => $this->eventType->value,
            'application_id' => $this->application->id,
            
            // Display data
            'title' => $this->eventType->title($this->isAdminNotification),
            'message' => $this->getMessage($userName, $divisionName),
            'icon' => $this->eventType->icon(),
            'color' => $this->eventType->color(),
            
            // Context data
            'user_id' => $this->application->user_id,
            'user_name' => $userName,
            'user_email' => $this->application->user->email ?? null,
            'division_id' => $this->application->division_id,
            'division_name' => $divisionName,
            'status' => $this->application->status,
            'status_label' => $this->getStatusLabel(),
            
            // Action URL
            'action_url' => $this->getActionUrl(),
            'action_text' => $this->getActionText(),
            
            // Metadata
            'metadata' => $this->metadata,
            'is_admin_notification' => $this->isAdminNotification,
            'created_at' => now()->toISOString(),
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
     * Get the type of the notification being broadcast.
     */
    public function broadcastType(): string
    {
        return 'registration.status.notification';
    }

    /**
     * Get the contextual message based on event type and recipient role.
     */
    private function getMessage(string $userName, string $divisionName): string
    {
        if ($this->isAdminNotification) {
            return match($this->eventType) {
                RegistrationEventType::APPLICATION_SUBMITTED,
                RegistrationEventType::NEW_REGISTRATION => "{$userName} mendaftar untuk posisi {$divisionName}",
                RegistrationEventType::SELECTION_IN_PROGRESS => "Proses seleksi {$userName} telah dimulai",
                RegistrationEventType::INTERVIEW_SCHEDULED => "Wawancara {$userName} telah dijadwalkan",
                RegistrationEventType::INTERVIEW_RESCHEDULED => "Jadwal wawancara {$userName} telah diubah",
                RegistrationEventType::APPLICATION_ACCEPTED => "{$userName} telah diterima untuk posisi {$divisionName}",
                RegistrationEventType::APPLICATION_REJECTED => "{$userName} tidak diterima untuk posisi {$divisionName}",
                RegistrationEventType::ACCEPTANCE_LETTER_READY => "Surat penerimaan {$userName} telah diupload",
                RegistrationEventType::APPLICATION_EXPIRED => "Pendaftaran {$userName} telah kedaluwarsa",
                RegistrationEventType::DOCUMENTS_COMPLETED => "{$userName} telah melengkapi semua dokumen pendaftaran",
            };
        }

        return match($this->eventType) {
            RegistrationEventType::APPLICATION_SUBMITTED => "Pendaftaran Anda untuk posisi {$divisionName} telah berhasil dikirim.",
            RegistrationEventType::SELECTION_IN_PROGRESS => "Proses seleksi untuk pendaftaran Anda telah dimulai.",
            RegistrationEventType::INTERVIEW_SCHEDULED => "Jadwal wawancara Anda telah ditentukan. Silakan cek detail di dashboard.",
            RegistrationEventType::INTERVIEW_RESCHEDULED => "Jadwal wawancara Anda telah diubah. Silakan cek jadwal baru di dashboard.",
            RegistrationEventType::APPLICATION_ACCEPTED => "Selamat! Anda telah diterima untuk posisi {$divisionName}.",
            RegistrationEventType::APPLICATION_REJECTED => "Mohon maaf, pendaftaran Anda untuk posisi {$divisionName} tidak dapat kami terima.",
            RegistrationEventType::ACCEPTANCE_LETTER_READY => "Surat penerimaan resmi Anda telah tersedia untuk diunduh.",
            RegistrationEventType::APPLICATION_EXPIRED => "Pendaftaran Anda telah melewati batas waktu yang ditentukan.",
            default => "Status pendaftaran Anda telah diperbarui.",
        };
    }

    /**
     * Get the action URL based on recipient role.
     */
    private function getActionUrl(): string
    {
        if ($this->isAdminNotification) {
            return url('/admin/applications/' . $this->application->id);
        }

        if ($this->eventType === RegistrationEventType::ACCEPTANCE_LETTER_READY) {
            return $this->metadata['download_url'] ?? url('/profile');
        }

        return url('/profile');
    }

    /**
     * Get the action button text.
     */
    private function getActionText(): string
    {
        if ($this->isAdminNotification) {
            return 'Review Pendaftaran';
        }

        return match($this->eventType) {
            RegistrationEventType::ACCEPTANCE_LETTER_READY => 'Download Surat',
            RegistrationEventType::INTERVIEW_SCHEDULED,
            RegistrationEventType::INTERVIEW_RESCHEDULED => 'Lihat Jadwal',
            default => 'Lihat Detail',
        };
    }

    /**
     * Get the status label from the application.
     */
    private function getStatusLabel(): string
    {
        try {
            $status = RegistrationStatus::from($this->application->status);
            return $status->label();
        } catch (\ValueError $e) {
            return ucfirst($this->application->status);
        }
    }
}

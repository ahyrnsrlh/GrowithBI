<?php

namespace App\Enums;

/**
 * Registration Event Type Enum
 * 
 * Defines all notification event types for the registration system.
 * Each event type has specific content for both users and admins.
 */
enum RegistrationEventType: string
{
    // User-facing events
    case APPLICATION_SUBMITTED = 'application_submitted';
    case SELECTION_IN_PROGRESS = 'selection_in_progress';
    case INTERVIEW_SCHEDULED = 'interview_scheduled';
    case INTERVIEW_RESCHEDULED = 'interview_rescheduled';
    case APPLICATION_ACCEPTED = 'application_accepted';
    case APPLICATION_REJECTED = 'application_rejected';
    case ACCEPTANCE_LETTER_READY = 'acceptance_letter_ready';
    case APPLICATION_EXPIRED = 'application_expired';

    // Admin-facing events
    case NEW_REGISTRATION = 'new_registration';
    case DOCUMENTS_COMPLETED = 'documents_completed';

    /**
     * Get the notification title based on event type and recipient role
     */
    public function title(bool $isAdmin = false): string
    {
        return match($this) {
            // User-facing titles
            self::APPLICATION_SUBMITTED => $isAdmin 
                ? 'Pendaftaran Baru Diterima' 
                : 'Pendaftaran Berhasil Dikirim',
            self::SELECTION_IN_PROGRESS => $isAdmin 
                ? 'Status Seleksi Diperbarui' 
                : 'Proses Seleksi Dimulai',
            self::INTERVIEW_SCHEDULED => $isAdmin 
                ? 'Wawancara Dijadwalkan' 
                : 'Jadwal Wawancara Anda',
            self::INTERVIEW_RESCHEDULED => $isAdmin 
                ? 'Jadwal Wawancara Diubah' 
                : 'Perubahan Jadwal Wawancara',
            self::APPLICATION_ACCEPTED => $isAdmin 
                ? 'Pendaftar Diterima' 
                : 'Selamat! Anda Diterima',
            self::APPLICATION_REJECTED => $isAdmin 
                ? 'Pendaftar Ditolak' 
                : 'Status Pendaftaran Anda',
            self::ACCEPTANCE_LETTER_READY => $isAdmin 
                ? 'Surat Penerimaan Diupload' 
                : 'Surat Penerimaan Tersedia',
            self::APPLICATION_EXPIRED => $isAdmin 
                ? 'Pendaftaran Kedaluwarsa' 
                : 'Pendaftaran Kedaluwarsa',

            // Admin-only titles
            self::NEW_REGISTRATION => 'Pendaftaran Baru',
            self::DOCUMENTS_COMPLETED => 'Dokumen Lengkap',
        };
    }

    /**
     * Get the notification icon (Heroicons name)
     */
    public function icon(): string
    {
        return match($this) {
            self::APPLICATION_SUBMITTED, 
            self::NEW_REGISTRATION => 'paper-airplane',
            self::SELECTION_IN_PROGRESS => 'document-search',
            self::INTERVIEW_SCHEDULED, 
            self::INTERVIEW_RESCHEDULED => 'calendar',
            self::APPLICATION_ACCEPTED => 'check-badge',
            self::APPLICATION_REJECTED => 'x-circle',
            self::ACCEPTANCE_LETTER_READY => 'document-download',
            self::APPLICATION_EXPIRED => 'clock',
            self::DOCUMENTS_COMPLETED => 'document-check',
        };
    }

    /**
     * Get the notification color
     */
    public function color(): string
    {
        return match($this) {
            self::APPLICATION_SUBMITTED, 
            self::NEW_REGISTRATION => 'blue',
            self::SELECTION_IN_PROGRESS => 'indigo',
            self::INTERVIEW_SCHEDULED, 
            self::INTERVIEW_RESCHEDULED => 'purple',
            self::APPLICATION_ACCEPTED => 'green',
            self::APPLICATION_REJECTED => 'red',
            self::ACCEPTANCE_LETTER_READY => 'emerald',
            self::APPLICATION_EXPIRED => 'gray',
            self::DOCUMENTS_COMPLETED => 'cyan',
        };
    }

    /**
     * Check if this event type is for admin recipients
     */
    public function isAdminEvent(): bool
    {
        return in_array($this, [
            self::NEW_REGISTRATION,
            self::DOCUMENTS_COMPLETED,
        ]);
    }

    /**
     * Check if this event type is for user recipients
     */
    public function isUserEvent(): bool
    {
        return in_array($this, [
            self::APPLICATION_SUBMITTED,
            self::SELECTION_IN_PROGRESS,
            self::INTERVIEW_SCHEDULED,
            self::INTERVIEW_RESCHEDULED,
            self::APPLICATION_ACCEPTED,
            self::APPLICATION_REJECTED,
            self::ACCEPTANCE_LETTER_READY,
            self::APPLICATION_EXPIRED,
        ]);
    }

    /**
     * Get the corresponding status for this event (if applicable)
     */
    public function toStatus(): ?RegistrationStatus
    {
        return match($this) {
            self::APPLICATION_SUBMITTED, 
            self::NEW_REGISTRATION => RegistrationStatus::MENUNGGU,
            self::SELECTION_IN_PROGRESS => RegistrationStatus::IN_REVIEW,
            self::INTERVIEW_SCHEDULED, 
            self::INTERVIEW_RESCHEDULED => RegistrationStatus::INTERVIEW_SCHEDULED,
            self::APPLICATION_ACCEPTED => RegistrationStatus::ACCEPTED,
            self::APPLICATION_REJECTED => RegistrationStatus::REJECTED,
            self::ACCEPTANCE_LETTER_READY => RegistrationStatus::LETTER_READY,
            self::APPLICATION_EXPIRED => RegistrationStatus::EXPIRED,
            self::DOCUMENTS_COMPLETED => null, // No status change
        };
    }

    /**
     * Get event type from status change
     */
    public static function fromStatusChange(
        RegistrationStatus $oldStatus, 
        RegistrationStatus $newStatus
    ): ?self {
        return match($newStatus) {
            RegistrationStatus::MENUNGGU => self::APPLICATION_SUBMITTED,
            RegistrationStatus::IN_REVIEW => self::SELECTION_IN_PROGRESS,
            RegistrationStatus::INTERVIEW_SCHEDULED => $oldStatus === RegistrationStatus::INTERVIEW_SCHEDULED 
                ? self::INTERVIEW_RESCHEDULED 
                : self::INTERVIEW_SCHEDULED,
            RegistrationStatus::ACCEPTED => self::APPLICATION_ACCEPTED,
            RegistrationStatus::REJECTED => self::APPLICATION_REJECTED,
            RegistrationStatus::LETTER_READY => self::ACCEPTANCE_LETTER_READY,
            RegistrationStatus::EXPIRED => self::APPLICATION_EXPIRED,
        };
    }

    /**
     * Get email subject
     */
    public function emailSubject(string $divisionName = ''): string
    {
        $prefix = '[GrowithBI] ';
        
        return match($this) {
            self::APPLICATION_SUBMITTED => $prefix . 'Pendaftaran Magang Berhasil Dikirim',
            self::SELECTION_IN_PROGRESS => $prefix . 'Proses Seleksi Magang Dimulai',
            self::INTERVIEW_SCHEDULED => $prefix . 'Jadwal Wawancara Magang',
            self::INTERVIEW_RESCHEDULED => $prefix . 'Perubahan Jadwal Wawancara',
            self::APPLICATION_ACCEPTED => $prefix . 'Selamat! Pendaftaran Magang Diterima - ' . $divisionName,
            self::APPLICATION_REJECTED => $prefix . 'Status Pendaftaran Magang Anda',
            self::ACCEPTANCE_LETTER_READY => $prefix . 'Surat Penerimaan Magang Tersedia',
            self::APPLICATION_EXPIRED => $prefix . 'Pendaftaran Magang Kedaluwarsa',
            self::NEW_REGISTRATION => $prefix . '[Admin] Pendaftaran Baru - ' . $divisionName,
            self::DOCUMENTS_COMPLETED => $prefix . '[Admin] Dokumen Pendaftaran Lengkap',
        };
    }

    /**
     * Get all values as array
     * 
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }
}

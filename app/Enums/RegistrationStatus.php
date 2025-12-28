<?php

namespace App\Enums;

/**
 * Registration Status Enum
 * 
 * Defines all possible states for an application throughout its lifecycle.
 * The status flow is:
 * 
 * menunggu -> in_review -> interview_scheduled -> accepted/rejected
 *                                              -> accepted -> letter_ready
 *                                              
 * Any status can transition to 'expired' if time limits are exceeded.
 */
enum RegistrationStatus: string
{
    case MENUNGGU = 'menunggu';
    case IN_REVIEW = 'in_review';
    case INTERVIEW_SCHEDULED = 'interview_scheduled';
    case ACCEPTED = 'accepted';
    case REJECTED = 'rejected';
    case LETTER_READY = 'letter_ready';
    case EXPIRED = 'expired';

    /**
     * Get the human-readable label for the status
     */
    public function label(): string
    {
        return match($this) {
            self::MENUNGGU => 'Menunggu Review',
            self::IN_REVIEW => 'Dalam Proses Seleksi',
            self::INTERVIEW_SCHEDULED => 'Wawancara Dijadwalkan',
            self::ACCEPTED => 'Diterima',
            self::REJECTED => 'Ditolak',
            self::LETTER_READY => 'Surat Penerimaan Tersedia',
            self::EXPIRED => 'Kedaluwarsa',
        };
    }

    /**
     * Get the description for the status
     */
    public function description(): string
    {
        return match($this) {
            self::MENUNGGU => 'Pendaftaran Anda sedang menunggu untuk ditinjau oleh tim kami.',
            self::IN_REVIEW => 'Dokumen Anda sedang dalam proses seleksi.',
            self::INTERVIEW_SCHEDULED => 'Jadwal wawancara telah ditentukan. Silakan cek email untuk detail.',
            self::ACCEPTED => 'Selamat! Anda telah diterima sebagai peserta magang.',
            self::REJECTED => 'Mohon maaf, pendaftaran Anda tidak dapat kami terima.',
            self::LETTER_READY => 'Surat penerimaan resmi Anda telah tersedia untuk diunduh.',
            self::EXPIRED => 'Pendaftaran telah melewati batas waktu yang ditentukan.',
        };
    }

    /**
     * Get the CSS color class for the status badge
     */
    public function color(): string
    {
        return match($this) {
            self::MENUNGGU => 'yellow',
            self::IN_REVIEW => 'blue',
            self::INTERVIEW_SCHEDULED => 'purple',
            self::ACCEPTED => 'green',
            self::REJECTED => 'red',
            self::LETTER_READY => 'emerald',
            self::EXPIRED => 'gray',
        };
    }

    /**
     * Get the Tailwind CSS classes for the status badge
     */
    public function badgeClasses(): string
    {
        return match($this) {
            self::MENUNGGU => 'bg-yellow-100 text-yellow-800 border-yellow-200',
            self::IN_REVIEW => 'bg-blue-100 text-blue-800 border-blue-200',
            self::INTERVIEW_SCHEDULED => 'bg-purple-100 text-purple-800 border-purple-200',
            self::ACCEPTED => 'bg-green-100 text-green-800 border-green-200',
            self::REJECTED => 'bg-red-100 text-red-800 border-red-200',
            self::LETTER_READY => 'bg-emerald-100 text-emerald-800 border-emerald-200',
            self::EXPIRED => 'bg-gray-100 text-gray-800 border-gray-200',
        };
    }

    /**
     * Get the icon name for the status (Heroicons)
     */
    public function icon(): string
    {
        return match($this) {
            self::MENUNGGU => 'clock',
            self::IN_REVIEW => 'document-search',
            self::INTERVIEW_SCHEDULED => 'calendar',
            self::ACCEPTED => 'check-circle',
            self::REJECTED => 'x-circle',
            self::LETTER_READY => 'document-download',
            self::EXPIRED => 'exclamation-circle',
        };
    }

    /**
     * Check if the status is a final status (no further transitions expected)
     */
    public function isFinal(): bool
    {
        return in_array($this, [
            self::REJECTED,
            self::LETTER_READY,
            self::EXPIRED,
        ]);
    }

    /**
     * Check if the status indicates active/pending application
     */
    public function isActive(): bool
    {
        return in_array($this, [
            self::MENUNGGU,
            self::IN_REVIEW,
            self::INTERVIEW_SCHEDULED,
        ]);
    }

    /**
     * Check if the status indicates successful acceptance
     */
    public function isAccepted(): bool
    {
        return in_array($this, [
            self::ACCEPTED,
            self::LETTER_READY,
        ]);
    }

    /**
     * Get the allowed status transitions from current status
     * 
     * @return array<RegistrationStatus>
     */
    public function allowedTransitions(): array
    {
        return match($this) {
            self::MENUNGGU => [
                self::IN_REVIEW,
                self::REJECTED,
                self::EXPIRED,
            ],
            self::IN_REVIEW => [
                self::INTERVIEW_SCHEDULED,
                self::ACCEPTED,
                self::REJECTED,
                self::EXPIRED,
            ],
            self::INTERVIEW_SCHEDULED => [
                self::ACCEPTED,
                self::REJECTED,
                self::EXPIRED,
            ],
            self::ACCEPTED => [
                self::LETTER_READY,
                self::EXPIRED,
            ],
            self::REJECTED => [],
            self::LETTER_READY => [],
            self::EXPIRED => [],
        };
    }

    /**
     * Check if transition to given status is allowed
     */
    public function canTransitionTo(RegistrationStatus $newStatus): bool
    {
        return in_array($newStatus, $this->allowedTransitions());
    }

    /**
     * Get all status values as array for validation rules
     * 
     * @return array<string>
     */
    public static function values(): array
    {
        return array_column(self::cases(), 'value');
    }

    /**
     * Get status options for dropdowns
     * 
     * @return array<array{value: string, label: string}>
     */
    public static function options(): array
    {
        return array_map(
            fn (self $status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            self::cases()
        );
    }

    /**
     * Get active statuses only (for filtering active applications)
     * 
     * @return array<string>
     */
    public static function activeValues(): array
    {
        return array_map(
            fn (self $status) => $status->value,
            array_filter(
                self::cases(),
                fn (self $status) => $status->isActive()
            )
        );
    }
}

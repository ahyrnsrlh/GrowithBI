<?php

namespace App\Models;

use App\Enums\RegistrationEventType;
use App\Enums\RegistrationStatus;
use App\Events\ApplicationStatusChanged;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Application extends Model
{
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'phone',
        'address',
        'university',
        'major',
        'semester',
        'gpa',
        'birth_date',
        'gender',
        'portfolio_url',
        'division_id',
        'motivation',
        'status',
        'cv_path',
        'surat_lamaran_path',
        'transkrip_path',
        'ktp_path',
        'interview_date',
        'interview_location',
        'notes',
        'admin_notes',
        'rejection_reason',
        'acceptance_letter_path',
        'acceptance_letter_uploaded_at',
        'uploaded_by',
        'reviewed_at',
        'reviewed_by',
        'last_notification_event',
        'last_notification_at',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
        'acceptance_letter_uploaded_at' => 'datetime',
        'reviewed_at' => 'datetime',
        'last_notification_at' => 'datetime',
        'semester' => 'integer',
        'gpa' => 'decimal:2',
        'birth_date' => 'date',
    ];

    /**
     * Get the status as enum
     */
    public function getStatusEnumAttribute(): ?RegistrationStatus
    {
        try {
            return RegistrationStatus::from($this->status);
        } catch (\ValueError $e) {
            return null;
        }
    }

    /**
     * Check if status transition is valid
     */
    public function canTransitionTo(RegistrationStatus $newStatus): bool
    {
        $currentStatus = $this->status_enum;
        if (!$currentStatus) {
            return true; // Allow any transition from unknown status
        }
        return $currentStatus->canTransitionTo($newStatus);
    }

    /**
     * Update status with validation and event dispatch
     * 
     * @param RegistrationStatus $newStatus
     * @param \App\Models\User|null $changedBy
     * @param array $metadata Additional data (interview_date, rejection_reason, etc.)
     * @return bool
     */
    public function transitionTo(
        RegistrationStatus $newStatus, 
        ?\App\Models\User $changedBy = null,
        array $metadata = []
    ): bool {
        $oldStatus = $this->status_enum ?? RegistrationStatus::MENUNGGU;
        
        // Validate transition
        if (!$this->canTransitionTo($newStatus)) {
            return false;
        }

        return DB::transaction(function () use ($newStatus, $oldStatus, $changedBy, $metadata) {
            // Update the status
            $updateData = [
                'status' => $newStatus->value,
                'reviewed_at' => now(),
            ];

            // Add interview data if scheduling interview
            if ($newStatus === RegistrationStatus::INTERVIEW_SCHEDULED) {
                if (isset($metadata['interview_date'])) {
                    $updateData['interview_date'] = $metadata['interview_date'];
                }
                if (isset($metadata['interview_location'])) {
                    $updateData['interview_location'] = $metadata['interview_location'];
                }
            }

            // Add rejection reason if rejecting
            if ($newStatus === RegistrationStatus::REJECTED && isset($metadata['rejection_reason'])) {
                $updateData['rejection_reason'] = $metadata['rejection_reason'];
            }

            // Add admin notes
            if (isset($metadata['admin_notes'])) {
                $updateData['admin_notes'] = $metadata['admin_notes'];
            }

            // Track reviewer
            if ($changedBy) {
                $updateData['reviewed_by'] = $changedBy->id;
            }

            $this->update($updateData);

            // Determine the event type from status change
            $eventType = RegistrationEventType::fromStatusChange($oldStatus, $newStatus);

            if ($eventType) {
                // Dispatch the event
                ApplicationStatusChanged::dispatch(
                    $this->fresh(['user', 'division']),
                    $eventType,
                    $oldStatus,
                    $newStatus,
                    $changedBy,
                    $metadata
                );

                // Track last notification
                $this->update([
                    'last_notification_event' => $eventType->value,
                    'last_notification_at' => now(),
                ]);
            }

            return true;
        });
    }

    /**
     * Relationships
     */
    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function letterUploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->whereIn('status', RegistrationStatus::activeValues());
    }

    public function scopeAccepted($query)
    {
        return $query->whereIn('status', [
            RegistrationStatus::ACCEPTED->value,
            RegistrationStatus::LETTER_READY->value,
        ]);
    }

    public function scopePending($query)
    {
        return $query->where('status', RegistrationStatus::MENUNGGU->value);
    }

    /**
     * Helper methods
     */
    public function isActive(): bool
    {
        return $this->status_enum?->isActive() ?? false;
    }

    public function isAccepted(): bool
    {
        return $this->status_enum?->isAccepted() ?? false;
    }

    public function isFinal(): bool
    {
        return $this->status_enum?->isFinal() ?? false;
    }

    public function hasAcceptanceLetter(): bool
    {
        return !empty($this->acceptance_letter_path);
    }

    public function canDownloadAcceptanceLetter(): bool
    {
        return $this->isAccepted() && $this->hasAcceptanceLetter();
    }

    /**
     * Get status badge with enum support
     */
    public function getStatusBadgeAttribute(): array
    {
        $status = $this->status_enum;
        
        if ($status) {
            return [
                'text' => $status->label(),
                'class' => $status->badgeClasses(),
                'color' => $status->color(),
                'icon' => $status->icon(),
            ];
        }

        // Fallback for unknown statuses
        return [
            'text' => ucfirst($this->status ?? 'Unknown'),
            'class' => 'bg-gray-100 text-gray-800 border-gray-200',
            'color' => 'gray',
            'icon' => 'question-mark-circle',
        ];
    }

    /**
     * Get status info for frontend
     */
    public function getStatusInfoAttribute(): array
    {
        $status = $this->status_enum;
        
        return [
            'value' => $this->status,
            'label' => $status?->label() ?? ucfirst($this->status),
            'description' => $status?->description() ?? '',
            'color' => $status?->color() ?? 'gray',
            'icon' => $status?->icon() ?? 'question-mark-circle',
            'badge_classes' => $status?->badgeClasses() ?? 'bg-gray-100 text-gray-800',
            'is_active' => $this->isActive(),
            'is_accepted' => $this->isAccepted(),
            'is_final' => $this->isFinal(),
            'can_download_letter' => $this->canDownloadAcceptanceLetter(),
        ];
    }

    /**
     * Get available status transitions for admin UI
     */
    public function getAvailableTransitionsAttribute(): array
    {
        $currentStatus = $this->status_enum;
        
        if (!$currentStatus) {
            return RegistrationStatus::options();
        }

        return array_map(
            fn (RegistrationStatus $status) => [
                'value' => $status->value,
                'label' => $status->label(),
            ],
            $currentStatus->allowedTransitions()
        );
    }
}

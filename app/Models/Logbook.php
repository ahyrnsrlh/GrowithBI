<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Logbook extends Model
{
    protected $fillable = [
        'user_id',
        'application_id',
        'division_id',
        'title',
        'date',
        'duration',
        'activity',
        'learning_outcome',
        'status',
        'approved_by',
        'notes',
        'attachment_path'
    ];

    protected $casts = [
        'date' => 'date',
        'duration' => 'decimal:1'
    ];

    // Relationships
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function approver(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(LogbookComment::class);
    }

    // Scopes
    public function scopeByUser($query, $userId)
    {
        return $query->where('user_id', $userId);
    }

    public function scopeByDivision($query, $divisionId)
    {
        return $query->where('division_id', $divisionId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopePendingReview($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    // Accessors & Mutators
    public function getActivitiesAttribute()
    {
        return $this->activity;
    }

    public function setActivitiesAttribute($value)
    {
        $this->attributes['activity'] = $value;
    }

    public function getLearningPointsAttribute()
    {
        return $this->learning_outcome;
    }

    public function setLearningPointsAttribute($value)
    {
        $this->attributes['learning_outcome'] = $value;
    }

    public function getDurationFormattedAttribute()
    {
        $hours = floor($this->duration);
        $minutes = ($this->duration - $hours) * 60;
        
        if ($hours > 0 && $minutes > 0) {
            return "{$hours} jam {$minutes} menit";
        } elseif ($hours > 0) {
            return "{$hours} jam";
        } elseif ($minutes > 0) {
            return "{$minutes} menit";
        }
        
        return "0 menit";
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'draft' => ['text' => 'Draft', 'class' => 'bg-gray-100 text-gray-800'],
            'submitted' => ['text' => 'Menunggu Review', 'class' => 'bg-yellow-100 text-yellow-800'],
            'approved' => ['text' => 'Disetujui', 'class' => 'bg-green-100 text-green-800'],
            'rejected' => ['text' => 'Ditolak', 'class' => 'bg-red-100 text-red-800'],
            'revision' => ['text' => 'Perlu Revisi', 'class' => 'bg-orange-100 text-orange-800'],
        ];

        return $badges[$this->status] ?? ['text' => $this->status, 'class' => 'bg-gray-100 text-gray-800'];
    }

    // Methods
    public function canBeEditedBy(User $user): bool
    {
        // Only the owner can edit draft logbooks
        return $this->user_id === $user->id && $this->status === 'draft';
    }

    public function canBeReviewedBy(User $user): bool
    {
        // Only mentors/admins of the same division can review
        return $user->hasRole(['admin', 'mentor']) && 
               ($user->hasRole('admin') || $user->division_id === $this->division_id);
    }

    public function submit(): bool
    {
        if ($this->status === 'draft') {
            $this->update([
                'status' => 'submitted',
                'submitted_at' => now()
            ]);
            return true;
        }
        return false;
    }

    public function approve(User $approver, string $feedback = null): bool
    {
        if ($this->status === 'submitted') {
            $this->update([
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $approver->id,
                'reviewed_at' => now(),
                'reviewed_by' => $approver->id,
                'mentor_feedback' => $feedback
            ]);
            return true;
        }
        return false;
    }

    public function reject(User $reviewer, string $feedback): bool
    {
        if ($this->status === 'submitted') {
            $this->update([
                'status' => 'rejected',
                'reviewed_at' => now(),
                'reviewed_by' => $reviewer->id,
                'mentor_feedback' => $feedback
            ]);
            return true;
        }
        return false;
    }

    public function requestRevision(User $reviewer, string $feedback): bool
    {
        if ($this->status === 'submitted') {
            $this->update([
                'status' => 'revision',
                'reviewed_at' => now(),
                'reviewed_by' => $reviewer->id,
                'mentor_feedback' => $feedback
            ]);
            return true;
        }
        return false;
    }
}

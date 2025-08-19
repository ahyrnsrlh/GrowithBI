<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Logbook extends Model
{
    protected $fillable = [
        'application_id',
        'activity',
        'learning_outcome',
        'date',
        'status',
        'approved_by',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    // Relationships
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    // Status helper methods
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isApproved(): bool
    {
        return $this->status === 'approved';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    // Scopes
    public function scopeByApplication($query, $applicationId)
    {
        return $query->where('application_id', $applicationId);
    }

    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }
}

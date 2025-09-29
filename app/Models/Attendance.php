<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class Attendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'date',
        'check_in',
        'check_out',
        'status',
        'latitude',
        'longitude',
        'location_address',
        'notes',
    ];

    protected $casts = [
        'date' => 'date',
        'check_in' => 'datetime',
        'check_out' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
    ];

    /**
     * Get the user that owns the attendance record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if user is late based on check-in time
     */
    public function isLate(): bool
    {
        if (!$this->check_in) return false;
        
        // Assuming office hours start at 8:00 AM
        $officeStartTime = Carbon::createFromTime(8, 0, 0);
        $checkInTime = Carbon::parse($this->check_in);
        
        return $checkInTime->isAfter($officeStartTime);
    }

    /**
     * Check if attendance is complete (both check-in and check-out)
     */
    public function isComplete(): bool
    {
        return !is_null($this->check_in) && !is_null($this->check_out);
    }

    /**
     * Get working duration in minutes
     */
    public function getWorkingDuration(): ?int
    {
        if (!$this->check_in || !$this->check_out) return null;
        
        $checkIn = Carbon::parse($this->check_in);
        $checkOut = Carbon::parse($this->check_out);
        
        return $checkOut->diffInMinutes($checkIn);
    }

    /**
     * Scope for filtering by date range
     */
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    /**
     * Scope for filtering by status
     */
    public function scopeByStatus($query, $status)
    {
        return $query->where('status', $status);
    }

    /**
     * Scope for current month attendances
     */
    public function scopeCurrentMonth($query)
    {
        return $query->whereMonth('date', Carbon::now()->month)
                    ->whereYear('date', Carbon::now()->year);
    }
}

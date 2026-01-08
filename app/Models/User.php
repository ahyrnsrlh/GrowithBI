<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Carbon\Carbon;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'division_id',
        'profile_photo_path',
        'phone',
        'position',
        'bio',
        'preferences',
        'address',
        'university',
        'major',
        'semester',
        'gpa',
        'birth_date',
        'gender',
        'portfolio_url',
        'surat_pengantar_path',
        'cv_path',
        'motivation_letter_path',
        'transkrip_path',
        'ktp_path',
        'npwp_path',
        'buku_rekening_path',
        'pas_foto_path',
        'face_descriptor',
        'face_registered_at',
        'two_factor_enabled',
        'two_factor_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'birth_date' => 'date',
            'gpa' => 'decimal:2',
            'semester' => 'integer',
            'preferences' => 'array',
            'face_registered_at' => 'datetime',
            'two_factor_enabled' => 'boolean',
            'two_factor_verified_at' => 'datetime',
        ];
    }

    /**
     * Get the applications for the user.
     */
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    /**
     * Get the division for the user (for mentors/admins).
     */
    public function division()
    {
        return $this->belongsTo(Division::class);
    }

    /**
     * Get the logbooks for the user.
     */
    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    /**
     * Get the logbook comments by the user.
     */
    public function logbookComments()
    {
        return $this->hasMany(LogbookComment::class);
    }

    /**
     * Get the reports for the user.
     */
    public function reports()
    {
        return $this->hasMany(Report::class);
    }

    /**
     * Check if user has a specific role
     * @param string|array $role
     * @return bool
     */
    public function hasRole($role)
    {
        if (is_array($role)) {
            return in_array($this->role, $role);
        }
        return $this->role === $role;
    }

    /**
     * Get role names for compatibility
     * @return \Illuminate\Support\Collection
     */
    public function getRoleNames()
    {
        return collect([$this->role]);
    }

    /**
     * Check if user is admin
     */
    public function isAdmin()
    {
        return $this->role === 'admin';
    }

    /**
     * Check if user is mentor
     */
    public function isMentor()
    {
        return $this->role === 'mentor';
    }

    /**
     * Check if user is participant
     */
    public function isParticipant()
    {
        return $this->role === 'participant';
    }

    /**
     * Statuses that indicate accepted application
     */
    private const ACCEPTED_STATUSES = ['accepted', 'letter_ready', 'diterima'];

    /**
     * Check if user has accepted application
     */
    public function hasAcceptedApplication()
    {
        return $this->applications()->whereIn('status', self::ACCEPTED_STATUSES)->exists();
    }

    /**
     * Get accepted application
     */
    public function getAcceptedApplication()
    {
        return $this->applications()->whereIn('status', self::ACCEPTED_STATUSES)->with('division')->first();
    }

    /**
     * Calculate logbook completion percentage
     */
    public function getLogbookCompletionPercentage()
    {
        $acceptedApp = $this->getAcceptedApplication();
        if (!$acceptedApp) return 0;

        $totalRequired = $this->calculateRequiredLogbookDays($acceptedApp);
        $completed = $this->logbooks()->where('status', 'approved')->count();

        return $totalRequired > 0 ? ($completed / $totalRequired) * 100 : 0;
    }

    /**
     * Calculate required logbook days based on internship period
     */
    private function calculateRequiredLogbookDays($application)
    {
        // Default to 60 working days if no specific period defined
        $startDate = $application->division->start_date ?? $application->created_at;
        $endDate = $application->division->end_date ?? $application->created_at->addDays(60);
        
        // Count weekdays only
        $workingDays = 0;
        $current = Carbon::parse($startDate);
        $end = Carbon::parse($endDate);
        
        while ($current->lte($end)) {
            if ($current->isWeekday()) {
                $workingDays++;
            }
            $current->addDay();
        }
        
        return $workingDays;
    }

    /**
     * Send the password reset notification.
     *
     * @param  string  $token
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetPasswordNotification($token));
    }

    /**
     * Get the attendances for the user.
     */
    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    /**
     * Get today's attendance record
     */
    public function todayAttendance()
    {
        return $this->attendances()->where('date', now()->toDateString())->first();
    }

    /**
     * Check if user can access attendance feature
     */
    public function canAccessAttendance(): bool
    {
        if ($this->role !== 'peserta') return false;
        
        // Check if user has accepted application (any of the accepted statuses)
        return $this->applications()
            ->whereIn('status', self::ACCEPTED_STATUSES)
            ->exists();
    }

    /**
     * Get attendance statistics for the user
     */
    public function getAttendanceStats($month = null, $year = null)
    {
        $query = $this->attendances();
        
        if ($month && $year) {
            $query->whereMonth('date', $month)->whereYear('date', $year);
        } else {
            $query->currentMonth();
        }
        
        $attendances = $query->get();
        
        return [
            'total_days' => $attendances->count(),
            'present_days' => $attendances->whereIn('status', ['On-Time', 'Late'])->count(),
            'on_time' => $attendances->where('status', 'On-Time')->count(),
            'late' => $attendances->where('status', 'Late')->count(),
            'absent' => $attendances->where('status', 'Absent')->count(),
            'not_checked_out' => $attendances->where('status', 'Not-Checked-Out')->count(),
        ];
    }

    // =========================================================================
    // TWO-FACTOR AUTHENTICATION METHODS
    // =========================================================================

    /**
     * Get the two-factor authentication codes for the user.
     */
    public function twoFactorCodes()
    {
        return $this->hasMany(TwoFactorCode::class);
    }

    /**
     * Get the trusted devices for the user.
     */
    public function trustedDevices()
    {
        return $this->hasMany(TrustedDevice::class);
    }

    /**
     * Get the two-factor audit logs for the user.
     */
    public function twoFactorAuditLogs()
    {
        return $this->hasMany(TwoFactorAuditLog::class);
    }

    /**
     * Check if two-factor authentication is enabled for this user.
     */
    public function hasTwoFactorEnabled(): bool
    {
        return $this->two_factor_enabled ?? true;
    }

    /**
     * Check if the user's role requires two-factor authentication.
     */
    public function requiresTwoFactor(): bool
    {
        return in_array($this->role, ['admin', 'pembimbing']);
    }

    /**
     * Get the active trusted devices count.
     */
    public function getActiveTrustedDevicesCount(): int
    {
        return $this->trustedDevices()->where('expires_at', '>', now())->count();
    }

    /**
     * Revoke all trusted devices (e.g., on password change).
     */
    public function revokeAllTrustedDevices(): int
    {
        return $this->trustedDevices()->delete();
    }
}

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
     * Check if user has accepted application
     */
    public function hasAcceptedApplication()
    {
        return $this->applications()->where('status', 'diterima')->exists();
    }

    /**
     * Get accepted application
     */
    public function getAcceptedApplication()
    {
        return $this->applications()->where('status', 'diterima')->with('division')->first();
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
}

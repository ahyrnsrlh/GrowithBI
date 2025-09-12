<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'requirements',
        'max_interns',
        'start_date',
        'end_date',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'is_active' => 'boolean',
        ];
    }

    // Relationships
    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function logbooks()
    {
        return $this->hasMany(Logbook::class);
    }

    // Helper methods
    public function getQuotaAttribute()
    {
        return $this->max_interns;
    }

    public function setQuotaAttribute($value)
    {
        $this->attributes['max_interns'] = $value;
    }

    public function getAvailableQuotaAttribute()
    {
        return $this->quota - $this->applications()->where('status', 'diterima')->count();
    }
}

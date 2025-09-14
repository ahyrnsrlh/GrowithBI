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
        'job_description',
        'requirements',
        'quota',
        'max_interns',
        'start_date',
        'end_date',
        'application_deadline',
        'selection_announcement',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
            'application_deadline' => 'date',
            'selection_announcement' => 'date',
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

    public function getJobDescriptionAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        // If already an array, return as is
        if (is_array($value)) {
            return $value;
        }
        
        // Try to decode JSON
        $decoded = json_decode($value, true);
        
        // If decoding failed, return as single item array
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [$value];
        }
        
        // If we have an array but it looks like character array (first element is single char and we have many elements)
        if (is_array($decoded) && count($decoded) > 5 && strlen($decoded[0]) === 1) {
            return [implode('', $decoded)];
        }
        
        return is_array($decoded) ? $decoded : [$value];
    }

    public function getRequirementsAttribute($value)
    {
        if (empty($value)) {
            return [];
        }
        
        // If already an array, return as is
        if (is_array($value)) {
            return $value;
        }
        
        // Try to decode JSON
        $decoded = json_decode($value, true);
        
        // If decoding failed, return as single item array
        if (json_last_error() !== JSON_ERROR_NONE) {
            return [$value];
        }
        
        // If we have an array but it looks like character array (first element is single char and we have many elements)
        if (is_array($decoded) && count($decoded) > 5 && strlen($decoded[0]) === 1) {
            return [implode('', $decoded)];
        }
        
        return is_array($decoded) ? $decoded : [$value];
    }
}

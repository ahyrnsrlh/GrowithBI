<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
        'start_date',
        'end_date',
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
        'notes'
    ];

    protected $casts = [
        'interview_date' => 'datetime',
        'semester' => 'integer',
        'gpa' => 'decimal:2',
        'start_date' => 'date',
        'end_date' => 'date',
        'birth_date' => 'date',
    ];

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

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['menunggu', 'dalam_review', 'wawancara']);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu' => ['text' => 'Menunggu Review', 'class' => 'bg-yellow-100 text-yellow-800'],
            'dalam_review' => ['text' => 'Dalam Review', 'class' => 'bg-blue-100 text-blue-800'],
            'wawancara' => ['text' => 'Tahap Wawancara', 'class' => 'bg-purple-100 text-purple-800'],
            'diterima' => ['text' => 'Diterima', 'class' => 'bg-green-100 text-green-800'],
            'ditolak' => ['text' => 'Ditolak', 'class' => 'bg-red-100 text-red-800'],
        ];

        return $badges[$this->status] ?? ['text' => $this->status, 'class' => 'bg-gray-100 text-gray-800'];
    }
}

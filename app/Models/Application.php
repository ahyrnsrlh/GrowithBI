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
        'acceptance_letter_path',
        'acceptance_letter_uploaded_at',
        'uploaded_by'
    ];

    protected $casts = [
        'interview_date' => 'datetime',
        'acceptance_letter_uploaded_at' => 'datetime',
        'semester' => 'integer',
        'gpa' => 'decimal:2',
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

    public function letterUploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function scopeActive($query)
    {
        return $query->whereIn('status', ['menunggu', 'in_review', 'interview_scheduled']);
    }

    public function getStatusBadgeAttribute()
    {
        $badges = [
            'menunggu' => ['text' => 'Menunggu Review', 'class' => 'bg-yellow-100 text-yellow-800'],
            'in_review' => ['text' => 'Dalam Review', 'class' => 'bg-blue-100 text-blue-800'],
            'interview_scheduled' => ['text' => 'Tahap Wawancara', 'class' => 'bg-purple-100 text-purple-800'],
            'accepted' => ['text' => 'Diterima', 'class' => 'bg-green-100 text-green-800'],
            'rejected' => ['text' => 'Ditolak', 'class' => 'bg-red-100 text-red-800'],
            'expired' => ['text' => 'Kedaluwarsa', 'class' => 'bg-gray-100 text-gray-800'],
        ];

        return $badges[$this->status] ?? ['text' => $this->status, 'class' => 'bg-gray-100 text-gray-800'];
    }
}

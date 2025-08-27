<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LogbookComment extends Model
{
    protected $fillable = [
        'logbook_id',
        'user_id',
        'comment',
        'type',
        'is_internal'
    ];

    protected $casts = [
        'is_internal' => 'boolean'
    ];

    // Relationships
    public function logbook(): BelongsTo
    {
        return $this->belongsTo(Logbook::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Scopes
    public function scopePublic($query)
    {
        return $query->where('is_internal', false);
    }

    public function scopeInternal($query)
    {
        return $query->where('is_internal', true);
    }

    public function scopeByType($query, $type)
    {
        return $query->where('type', $type);
    }

    // Accessors
    public function getTypeBadgeAttribute()
    {
        $badges = [
            'comment' => ['text' => 'Komentar', 'class' => 'bg-blue-100 text-blue-800'],
            'feedback' => ['text' => 'Feedback', 'class' => 'bg-green-100 text-green-800'],
            'revision_request' => ['text' => 'Permintaan Revisi', 'class' => 'bg-orange-100 text-orange-800'],
        ];

        return $badges[$this->type] ?? ['text' => $this->type, 'class' => 'bg-gray-100 text-gray-800'];
    }
}

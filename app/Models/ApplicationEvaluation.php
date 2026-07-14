<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ApplicationEvaluation extends Model
{
    protected $fillable = [
        'application_id',
        'competency_score',
        'motivation_score',
        'interview_score',
        'competency_weight',
        'motivation_weight',
        'interview_weight',
        'final_score',
        'recommendation_level',
        'reviewer_notes',
        'reviewer_id',
        'score_history',
    ];

    protected $casts = [
        'competency_score' => 'decimal:2',
        'motivation_score' => 'decimal:2',
        'interview_score' => 'decimal:2',
        'competency_weight' => 'decimal:2',
        'motivation_weight' => 'decimal:2',
        'interview_weight' => 'decimal:2',
        'final_score' => 'decimal:2',
        'score_history' => 'array',
    ];

    /**
     * Get the application that owns the evaluation.
     */
    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    /**
     * Get the reviewer (user) who evaluated the application.
     */
    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewer_id');
    }
}

<?php

namespace App\Services;

use App\Models\ApplicationEvaluation;
use InvalidArgumentException;

class SelectionScoreService
{
    /**
     * Validate if a score is within 0-100.
     */
    public function validateScoreRange(float $score): void
    {
        if ($score < 0 || $score > 100) {
            throw new InvalidArgumentException('Nilai harus berada di antara 0 dan 100.');
        }
    }

    /**
     * Calculate weighted score.
     */
    public function calculateWeightedScore(float $score, float $weight): float
    {
        $this->validateScoreRange($score);
        return round(($score * $weight) / 100, 2);
    }

    /**
     * Calculate final score based on competency, motivation, interview scores and their weights.
     */
    public function calculateFinalScore(float $competency, float $motivation, float $interview, array $weights = []): float
    {
        $weights = empty($weights) ? config('selection.weights', ['competency' => 40, 'motivation' => 30, 'interview' => 30]) : $weights;
        
        $compWeight = $weights['competency'] ?? 40;
        $motWeight = $weights['motivation'] ?? 30;
        $intWeight = $weights['interview'] ?? 30;

        if (($compWeight + $motWeight + $intWeight) !== 100) {
            throw new InvalidArgumentException('Total bobot penilaian harus sama dengan 100%.');
        }

        $weightedComp = $this->calculateWeightedScore($competency, $compWeight);
        $weightedMot = $this->calculateWeightedScore($motivation, $motWeight);
        $weightedInt = $this->calculateWeightedScore($interview, $intWeight);

        return round($weightedComp + $weightedMot + $weightedInt, 2);
    }

    /**
     * Generate recommendation level based on final score.
     */
    public function generateRecommendationLevel(float $finalScore): string
    {
        if ($finalScore >= 90) {
            return 'Excellent Candidate';
        } elseif ($finalScore >= 80) {
            return 'Recommended';
        } elseif ($finalScore >= 70) {
            return 'Recommended with Consideration';
        } else {
            return 'Not Recommended';
        }
    }

    /**
     * Prepare score summary for frontend visualization.
     */
    public function prepareScoreSummaryForFrontend($evaluation): array
    {
        if (!$evaluation) {
            return [];
        }

        $compWeight = $evaluation->competency_weight;
        $motWeight = $evaluation->motivation_weight;
        $intWeight = $evaluation->interview_weight;

        return [
            'id' => $evaluation->id,
            'application_id' => $evaluation->application_id,
            'final_score' => (float) $evaluation->final_score,
            'recommendation_level' => $evaluation->recommendation_level,
            'reviewer_notes' => $evaluation->reviewer_notes,
            'reviewer_name' => $evaluation->reviewer?->name ?? 'Reviewer',
            'evaluation_date' => $evaluation->updated_at->isoFormat('D MMMM Y'),
            'criteria' => [
                [
                    'name' => 'Competency',
                    'raw_score' => (float) $evaluation->competency_score,
                    'weight' => (float) $compWeight,
                    'weighted_score' => $this->calculateWeightedScore($evaluation->competency_score, $compWeight),
                ],
                [
                    'name' => 'Motivation Letter',
                    'raw_score' => (float) $evaluation->motivation_score,
                    'weight' => (float) $motWeight,
                    'weighted_score' => $this->calculateWeightedScore($evaluation->motivation_score, $motWeight),
                ],
                [
                    'name' => 'Interview',
                    'raw_score' => (float) $evaluation->interview_score,
                    'weight' => (float) $intWeight,
                    'weighted_score' => $this->calculateWeightedScore($evaluation->interview_score, $intWeight),
                ],
            ]
        ];
    }
}

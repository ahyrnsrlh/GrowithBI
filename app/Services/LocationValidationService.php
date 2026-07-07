<?php

namespace App\Services;

use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LocationValidationService
{
    /**
     * Validate the location based solely on radius.
     *
     * @param float $latitude
     * @param float $longitude
     * @param float|null $accuracy
     * @return array
     */
    public function validateRadius(float $latitude, float $longitude, ?float $accuracy = null): array
    {
        $officeLat = config('attendance.office.latitude', -5.4194538);
        $officeLon = config('attendance.office.longitude', 105.2604370);
        $radiusMax = config('attendance.radius', 500);

        $distance = $this->calculateDistance($officeLat, $officeLon, $latitude, $longitude);
        $passed = $distance <= $radiusMax;
        
        $notes = [];
        if (!$passed) {
            $notes[] = "Anda berada di luar area presensi yang diizinkan.";
        }

        // Warn if GPS accuracy is poor, but do NOT block
        if ($accuracy !== null && $accuracy > 100) {
            $notes[] = "Sinyal GPS kurang akurat (" . round($accuracy, 1) . "m).";
        }

        return [
            'passed' => $passed,
            'distance' => $distance,
            'notes' => implode('; ', $notes),
        ];
    }

    /**
     * Calculate distance between two coordinates using Haversine formula (meters).
     */
    public function calculateDistance(float $lat1, float $lon1, float $lat2, float $lon2): float
    {
        $earthRadius = 6371000; // meters

        $lat1Rad = deg2rad($lat1);
        $lat2Rad = deg2rad($lat2);
        $deltaLatRad = deg2rad($lat2 - $lat1);
        $deltaLonRad = deg2rad($lon2 - $lon1);

        $a = sin($deltaLatRad / 2) ** 2
            + cos($lat1Rad) * cos($lat2Rad) * sin($deltaLonRad / 2) ** 2;

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}

<?php

namespace App\Services;

use App\Models\User;
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class LocationValidationService
{
    /**
     * Validate the entire location payload.
     *
     * @param User $user
     * @param array $payload
     * @return array
     */
    public function validateAll(User $user, array $payload): array
    {
        $latitude = $payload['latitude'] ?? null;
        $longitude = $payload['longitude'] ?? null;
        $accuracy = $payload['gps_accuracy'] ?? null;
        $samples = $payload['samples'] ?? [];
        $coordinateStability = $payload['coordinate_stability'] ?? null;

        $notes = [];
        $suspicious = false;
        $passed = true;

        // Configs
        $maxAccuracy = config('attendance.gps_accuracy_max', 50);
        $stabilityMax = config('attendance.stability_max', 0.0003);
        $officeLat = config('attendance.office.latitude', -5.4194538);
        $officeLon = config('attendance.office.longitude', 105.2604370);
        $radiusMax = config('attendance.radius', 500);

        // 1. Accuracy Check
        $accuracyPassed = true;
        if ($accuracy !== null) {
            if ($accuracy > $maxAccuracy) {
                $accuracyPassed = false;
                $passed = false;
                $notes[] = "Akurasi GPS Anda terlalu rendah. Silakan aktifkan mode Lokasi Akurasi Tinggi dan berpindah ke area terbuka.";
            }
        } else {
            $accuracyPassed = false;
            $passed = false;
            $notes[] = "Akurasi GPS Anda terlalu rendah. Silakan aktifkan mode Lokasi Akurasi Tinggi dan berpindah ke area terbuka.";
        }

        // 2. Stability Check
        $stabilityPassed = true;
        $calculatedStability = 0.0;
        if (!empty($samples)) {
            $calculatedStability = $this->calculateStabilityValue($samples);
            if ($calculatedStability > $stabilityMax) {
                $stabilityPassed = false;
                $passed = false;
                $notes[] = "Lokasi Anda belum stabil. Mohon tunggu beberapa saat hingga sistem memperoleh lokasi yang lebih akurat.";
            }
        } elseif ($coordinateStability !== null) {
            $calculatedStability = (float)$coordinateStability;
            if ($calculatedStability > $stabilityMax) {
                $stabilityPassed = false;
                $passed = false;
                $notes[] = "Lokasi Anda belum stabil. Mohon tunggu beberapa saat hingga sistem memperoleh lokasi yang lebih akurat.";
            }
        } else {
            $stabilityPassed = false;
            $passed = false;
            $notes[] = "Lokasi Anda belum stabil. Mohon tunggu beberapa saat hingga sistem memperoleh lokasi yang lebih akurat.";
        }

        // 3. Radius Validation
        $radiusPassed = false;
        $distance = 0.0;
        if ($latitude !== null && $longitude !== null) {
            $distance = $this->calculateDistance($officeLat, $officeLon, $latitude, $longitude);
            if ($distance <= $radiusMax) {
                $radiusPassed = true;
            } else {
                $passed = false;
                $notes[] = "Anda berada di luar area presensi yang diizinkan.";
            }
        } else {
            $passed = false;
            $notes[] = "Anda berada di luar area presensi yang diizinkan.";
        }

        // 4. Movement Validation (Soft check initially, but hard reject if impossible speed/suspicious)
        $movementCheck = ['passed' => true, 'speed_kmh' => 0.0, 'suspicious' => false];
        if ($latitude !== null && $longitude !== null && $passed) {
            $movementCheck = $this->detectSuspiciousMovement($user, $latitude, $longitude);
            if ($movementCheck['suspicious']) {
                $suspicious = true;
                $passed = false; // REJECT attendance on suspicious location/impossible speed
                $notes[] = "Sistem mendeteksi lokasi yang tidak valid. Presensi dibatalkan.";
            }
        }

        // If any of the hard validations failed, marked validation status as failed
        $validationStatus = 'passed';
        if (!$passed) {
            $validationStatus = 'failed';
        } elseif ($suspicious) {
            $validationStatus = 'suspicious';
        }

        return [
            'passed' => $passed,
            'suspicious' => $suspicious,
            'status' => $validationStatus,
            'notes' => implode('; ', $notes),
            'coordinate_stability' => $calculatedStability,
            'layers' => [
                'accuracy' => [
                    'passed' => $accuracyPassed,
                    'value' => $accuracy,
                    'threshold' => $maxAccuracy
                ],
                'stability' => [
                    'passed' => $stabilityPassed,
                    'value' => $calculatedStability,
                    'threshold' => $stabilityMax
                ],
                'radius' => [
                    'passed' => $radiusPassed,
                    'distance_m' => $distance,
                    'threshold' => $radiusMax
                ],
                'movement' => $movementCheck
            ]
        ];
    }

    /**
     * Compute stability value (max standard deviation of lats and lons).
     */
    private function calculateStabilityValue(array $samples): float
    {
        $lats = [];
        $lons = [];
        foreach ($samples as $sample) {
            if (is_array($sample)) {
                $lats[] = (float)($sample['latitude'] ?? 0);
                $lons[] = (float)($sample['longitude'] ?? 0);
            } elseif (is_object($sample)) {
                $lats[] = (float)($sample->latitude ?? 0);
                $lons[] = (float)($sample->longitude ?? 0);
            }
        }

        if (count($lats) <= 1) {
            return 0.0;
        }

        $stdDevLat = $this->calculateStdDev($lats);
        $stdDevLon = $this->calculateStdDev($lons);

        return (float)max($stdDevLat, $stdDevLon);
    }

    /**
     * Compute standard deviation of a numeric array.
     */
    private function calculateStdDev(array $values): float
    {
        $count = count($values);
        if ($count <= 1) {
            return 0.0;
        }

        $mean = array_sum($values) / $count;
        $variance = 0.0;

        foreach ($values as $val) {
            $variance += pow($val - $mean, 2);
        }

        return (float)sqrt($variance / ($count - 1));
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

    /**
     * Detect suspicious movement comparing to last check-in/check-out.
     */
    public function detectSuspiciousMovement(User $user, float $lat, float $lon): array
    {
        // Find user's last attendance record
        $lastAttendance = Attendance::where('user_id', $user->id)
            ->where(function ($query) {
                $query->whereNotNull('check_in')->orWhereNotNull('check_out');
            })
            ->orderByDesc('date')
            ->orderByDesc('id')
            ->first();

        if (!$lastAttendance) {
            return ['passed' => true, 'speed_kmh' => 0.0, 'suspicious' => false];
        }

        // Determine coordinates and timestamp of last activity
        $lastLat = null;
        $lastLon = null;
        $lastTime = null;

        if ($lastAttendance->check_out && $lastAttendance->checkout_latitude !== null) {
            $lastLat = (float)$lastAttendance->checkout_latitude;
            $lastLon = (float)$lastAttendance->checkout_longitude;
            $lastTime = Carbon::parse($lastAttendance->check_out);
        } elseif ($lastAttendance->check_in) {
            $lastLat = (float)$lastAttendance->latitude;
            $lastLon = (float)$lastAttendance->longitude;
            $lastTime = Carbon::parse($lastAttendance->check_in);
        }

        if ($lastLat === null || $lastLon === null || $lastTime === null) {
            return ['passed' => true, 'speed_kmh' => 0.0, 'suspicious' => false];
        }

        $currentTime = Carbon::now('Asia/Jakarta');
        $timeDiffSeconds = $currentTime->diffInSeconds($lastTime);

        // Compute distance in meters
        $distance = $this->calculateDistance($lastLat, $lastLon, $lat, $lon);

        if ($timeDiffSeconds <= 0) {
            // Same second attendance attempt at different location -> highly suspicious if distance is > 10m
            if ($distance > 10) {
                return [
                    'passed' => false,
                    'speed_kmh' => 9999.0,
                    'suspicious' => true,
                    'reason' => "Simultaneous attendances from different coordinates (distance: " . round($distance, 1) . "m)"
                ];
            }
            return ['passed' => true, 'speed_kmh' => 0.0, 'suspicious' => false];
        }

        // Speed in km/h
        $speedKmh = ($distance / 1000) / ($timeDiffSeconds / 3600);
        $maxSpeed = config('attendance.max_speed_kmh', 150);

        if ($speedKmh > $maxSpeed) {
            return [
                'passed' => false,
                'speed_kmh' => $speedKmh,
                'suspicious' => true,
                'reason' => "Impossible travel speed: " . round($speedKmh, 1) . " km/h (max: {$maxSpeed} km/h, distance: " . round($distance, 1) . "m)"
            ];
        }

        return [
            'passed' => true,
            'speed_kmh' => $speedKmh,
            'suspicious' => false
        ];
    }
}

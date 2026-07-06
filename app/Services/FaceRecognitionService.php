<?php

namespace App\Services;

use App\Models\Attendance;
use App\Models\User;
use App\Notifications\AttendanceNotification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class FaceRecognitionService
{
    /**
     * Face match threshold — lower = stricter.
     * Updated for stricter verification.
     */
    public const MATCH_THRESHOLD = 0.45;

    /**
     * Descriptor length expected from face-api.js
     */
    public const DESCRIPTOR_LENGTH = 128;

    /**
     * Verify that the supplied face descriptor matches the user's registered face.
     *
     * @param  User  $user
     * @param  array $descriptor  128-dimensional float array from face-api.js
     * @return array{success: bool, message: string, distance: float|null, confidence: float|null}
     */
    public function verifyFace(User $user, array $descriptor): array
    {
        if (empty($user->face_descriptor)) {
            return [
                'success'    => false,
                'message'    => 'Wajah Anda belum terdaftar. Silakan daftarkan wajah terlebih dahulu sebelum melakukan absensi.',
                'distance'   => null,
                'confidence' => null,
            ];
        }

        $storedDescriptor = json_decode($user->face_descriptor, true);

        if (!$storedDescriptor || count($storedDescriptor) !== self::DESCRIPTOR_LENGTH) {
            Log::error('FaceRecognitionService: invalid stored face descriptor', [
                'user_id'        => $user->id,
                'stored_length'  => count($storedDescriptor ?? []),
            ]);

            return [
                'success'    => false,
                'message'    => 'Data wajah tidak valid. Silakan hubungi admin untuk registrasi ulang.',
                'distance'   => null,
                'confidence' => null,
            ];
        }

        if (count($descriptor) !== self::DESCRIPTOR_LENGTH) {
            return [
                'success'    => false,
                'message'    => 'Descriptor wajah tidak valid (panjang tidak sesuai).',
                'distance'   => null,
                'confidence' => null,
            ];
        }

        $distance   = $this->calculateEuclideanDistance($descriptor, $storedDescriptor);
        $confidence = $this->distanceToConfidence($distance);
        $isMatch    = $distance < self::MATCH_THRESHOLD;

        Log::info('FaceRecognitionService: face verification attempt', [
            'user_id'    => $user->id,
            'distance'   => round($distance, 4),
            'threshold'  => self::MATCH_THRESHOLD,
            'confidence' => round($confidence, 2),
            'match'      => $isMatch,
            'timestamp'  => now(),
        ]);

        if (!$isMatch) {
            return [
                'success'    => false,
                'message'    => '❌ Verifikasi wajah gagal! Wajah tidak cocok. Pastikan Anda yang melakukan absensi.',
                'distance'   => $distance,
                'confidence' => $confidence,
            ];
        }

        return [
            'success'    => true,
            'message'    => 'Verifikasi wajah berhasil.',
            'distance'   => $distance,
            'confidence' => $confidence,
        ];
    }

    /**
     * Enroll (or re-enroll) a user's face from multiple descriptor samples.
     * Samples are averaged to produce a single representative descriptor.
     *
     * @param  User    $user
     * @param  array[] $descriptors  Array of 128-float arrays (1–5 samples recommended)
     * @return void
     */
    public function enrollFace(User $user, array $descriptors): void
    {
        $averaged = $this->averageDescriptors($descriptors);

        $user->face_descriptor    = json_encode($averaged);
        $user->face_registered_at = Carbon::now();
        $user->save();

        Log::info('FaceRecognitionService: face enrolled', [
            'user_id'      => $user->id,
            'sample_count' => count($descriptors),
            'timestamp'    => now(),
        ]);

        // Notify user of successful enrollment
        try {
            $tempAttendance          = new Attendance();
            $tempAttendance->user_id = $user->id;
            $tempAttendance->date    = Carbon::now('Asia/Jakarta')->toDateString();
            $user->notify(new AttendanceNotification($tempAttendance, 'face_registered'));
        } catch (\Exception $e) {
            Log::error('FaceRecognitionService: notification failed after enrollment', [
                'user_id' => $user->id,
                'error'   => $e->getMessage(),
            ]);
        }
    }

    /**
     * Average multiple 128-dimensional descriptors into one.
     *
     * @param  array[] $descriptors
     * @return float[]
     */
    public function averageDescriptors(array $descriptors): array
    {
        $count  = count($descriptors);
        $result = array_fill(0, self::DESCRIPTOR_LENGTH, 0.0);

        foreach ($descriptors as $descriptor) {
            for ($i = 0; $i < self::DESCRIPTOR_LENGTH; $i++) {
                $result[$i] += (float) $descriptor[$i];
            }
        }

        for ($i = 0; $i < self::DESCRIPTOR_LENGTH; $i++) {
            $result[$i] /= $count;
        }

        return $result;
    }

    /**
     * Calculate Euclidean distance between two 128-dimensional face descriptors.
     *
     * @param  float[] $a
     * @param  float[] $b
     * @return float
     */
    public function calculateEuclideanDistance(array $a, array $b): float
    {
        if (count($a) !== self::DESCRIPTOR_LENGTH || count($b) !== self::DESCRIPTOR_LENGTH) {
            throw new \InvalidArgumentException(
                'Face descriptors must be ' . self::DESCRIPTOR_LENGTH . '-dimensional arrays'
            );
        }

        $sum = 0.0;
        for ($i = 0; $i < self::DESCRIPTOR_LENGTH; $i++) {
            $diff = $a[$i] - $b[$i];
            $sum += $diff * $diff;
        }

        return sqrt($sum);
    }

    /**
     * Convert Euclidean distance to a 0-100 confidence percentage.
     * Distance 0   → 100% confidence (perfect match)
     * Distance ≥1  → 0%  confidence (totally different)
     *
     * @param  float $distance
     * @return float  0–100
     */
    public function distanceToConfidence(float $distance): float
    {
        $confidence = max(0, (1 - $distance) * 100);
        return round($confidence, 2);
    }
}

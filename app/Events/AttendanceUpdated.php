<?php

namespace App\Events;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired after check-in OR check-out is saved.
 *
 * Broadcasting decisions:
 * - Broadcasts on BOTH 'attendance-channel' (admin table) AND 'admin-maps'
 *   (existing maps page) so both consumers stay in sync without firing
 *   two separate events.
 * - ShouldBroadcastNow: skips the queue for immediate delivery.
 * - broadcastWith() returns a unified payload that satisfies both consumers.
 */
class AttendanceUpdated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Attendance $attendance;

    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance->loadMissing([
            'user.division',
            'user.acceptedApplication.division:id,name',
        ]);
    }

    /**
     * Broadcast on the admin table channel AND the existing maps channel.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('attendance-channel'),
            new Channel('admin-maps'),
        ];
    }

    /**
     * Custom event name: listened to on the frontend as '.attendance.updated'.
     */
    public function broadcastAs(): string
    {
        return 'attendance.updated';
    }

    /**
     * Unified payload for both the admin table and the maps page.
     * Includes all fields needed by either consumer.
     */
    public function broadcastWith(): array
    {
        $division = $this->resolveParticipantDivision($this->attendance->user);
        $divisionName = $division?->name ?? 'N/A';

        return [
            'attendance' => [
                // --- Table fields ---
                'id'               => $this->attendance->id,
                'date'             => $this->attendance->date->format('Y-m-d'),
                'date_formatted'   => $this->attendance->date->format('d M Y'),
                'check_in'         => $this->attendance->check_in
                    ? $this->attendance->check_in->format('H:i:s')
                    : null,
                'check_out'        => $this->attendance->check_out
                    ? $this->attendance->check_out->format('H:i:s')
                    : null,
                'status'           => $this->attendance->status,
                'working_duration' => $this->attendance->getWorkingDuration(),
                'user' => [
                    'id'       => $this->attendance->user->id,
                    'name'     => $this->attendance->user->name,
                    'email'    => $this->attendance->user->email,
                    'division' => $division ? [
                        'id'   => $division->id,
                        'name' => $division->name,
                    ] : null,
                ],
                'location' => [
                    'address'   => $this->attendance->location_address,
                    'latitude'  => $this->attendance->latitude,
                    'longitude' => $this->attendance->longitude,
                ],
                'photos' => [
                    'checkin'  => $this->attendance->photo_checkin_url,
                    'checkout' => $this->attendance->photo_checkout_url,
                ],

                // --- Maps-page-specific fields (kept for backward compatibility) ---
                'user_id'          => $this->attendance->user_id,
                'user_name'        => $this->attendance->user->name,
                'division'         => $divisionName,
                'check_in_time'    => $this->attendance->check_in,
                'check_out_time'   => $this->attendance->check_out,
                'latitude'         => (float) $this->attendance->latitude,
                'longitude'        => (float) $this->attendance->longitude,
                'is_valid_location' => $this->calculateIsValidLocation(
                    $this->attendance->latitude,
                    $this->attendance->longitude
                ),
                'updated_at'       => $this->attendance->updated_at->toISOString(),
            ],
        ];
    }

    private function resolveParticipantDivision(User $user): ?Division
    {
        return $user->division ?? $user->acceptedApplication?->division;
    }

    private function calculateIsValidLocation($latitude, $longitude): bool
    {
        if (!$latitude || !$longitude) {
            return false;
        }

        $officeLat = -5.3971;
        $officeLng = 105.2669;
        $radius    = 200;

        $distance = $this->calculateDistance($officeLat, $officeLng, $latitude, $longitude);

        return $distance <= $radius;
    }

    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earthRadius = 6371000;

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat / 2) * sin($dLat / 2)
            + cos(deg2rad($lat1)) * cos(deg2rad($lat2))
            * sin($dLon / 2) * sin($dLon / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        return $earthRadius * $c;
    }
}

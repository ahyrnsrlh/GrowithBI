<?php

namespace App\Events;

use App\Models\Attendance;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AttendanceUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $attendance;

    /**
     * Create a new event instance.
     */
    public function __construct(Attendance $attendance)
    {
        $this->attendance = $attendance->load(['user', 'user.division']);
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('admin-maps'),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'attendance.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'attendance' => [
                'id' => $this->attendance->id,
                'user_id' => $this->attendance->user_id,
                'user_name' => $this->attendance->user->name,
                'division' => $this->attendance->user->division->name ?? 'N/A',
                'check_in_time' => $this->attendance->check_in,
                'check_out_time' => $this->attendance->check_out,
                'latitude' => (float) $this->attendance->check_in_latitude,
                'longitude' => (float) $this->attendance->check_in_longitude,
                'status' => $this->attendance->status,
                'is_valid_location' => $this->calculateIsValidLocation(
                    $this->attendance->check_in_latitude,
                    $this->attendance->check_in_longitude
                ),
                'updated_at' => $this->attendance->updated_at->toISOString(),
            ]
        ];
    }

    /**
     * Check if location is within valid radius from office
     */
    private function calculateIsValidLocation($latitude, $longitude): bool
    {
        if (!$latitude || !$longitude) return false;

        // Office coordinates (Bank Indonesia KPw Lampung)
        $officeLat = -5.3971;
        $officeLng = 105.2669;
        $radius = 200; // meters

        $distance = $this->calculateDistance($officeLat, $officeLng, $latitude, $longitude);
        
        return $distance <= $radius;
    }

    /**
     * Calculate distance between two coordinates using Haversine formula
     */
    private function calculateDistance($lat1, $lon1, $lat2, $lon2): float
    {
        $earth_radius = 6371000; // Earth radius in meters

        $dLat = deg2rad($lat2 - $lat1);
        $dLon = deg2rad($lon2 - $lon1);

        $a = sin($dLat/2) * sin($dLat/2) + cos(deg2rad($lat1)) * cos(deg2rad($lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));

        return $earth_radius * $c;
    }
}

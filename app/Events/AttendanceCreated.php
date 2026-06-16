<?php

namespace App\Events;

use App\Models\Attendance;
use App\Models\Division;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * Fired after a participant successfully performs check-in.
 *
 * Broadcasting decisions:
 * - ShouldBroadcastNow: skips the queue for instant delivery. The payload
 *   is tiny so the overhead of a queue worker round-trip is not justified.
 * - Public channel 'attendance-channel': the admin index page is already
 *   protected by auth middleware; we don't need an extra auth gate on the
 *   channel itself, which would require a presence/private channel and an
 *   extra round-trip for authentication.
 * - broadcastAs() returns a dot-namespaced name so that the JS listener
 *   matches with .listen('.attendance.created').
 */
class AttendanceCreated implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public Attendance $attendance;

    public function __construct(Attendance $attendance)
    {
        // Eagerly load everything we need so broadcastWith() does not hit the DB.
        $this->attendance = $attendance->loadMissing([
            'user.division',
            'user.acceptedApplication.division:id,name',
        ]);
    }

    /**
     * The public channel that the admin table listens to.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('attendance-channel'),
        ];
    }

    /**
     * Custom event name: listened to on the frontend as '.attendance.created'.
     */
    public function broadcastAs(): string
    {
        return 'attendance.created';
    }

    /**
     * Lightweight payload — only the fields rendered in the table row.
     * Heavy data (notes, face descriptors, etc.) is intentionally omitted.
     */
    public function broadcastWith(): array
    {
        $division = $this->resolveParticipantDivision($this->attendance->user);

        return [
            'attendance' => [
                'id'               => $this->attendance->id,
                'date'             => $this->attendance->date->format('Y-m-d'),
                'date_formatted'   => $this->attendance->date->format('d M Y'),
                'check_in'         => $this->attendance->check_in
                    ? $this->attendance->check_in->format('H:i:s')
                    : null,
                'check_out'        => null, // always null on creation
                'status'           => $this->attendance->status,
                'working_duration' => null, // no duration yet
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
                    'checkout' => null,
                ],
            ],
        ];
    }

    /**
     * Resolve participant division with fallback to their accepted application.
     */
    private function resolveParticipantDivision(User $user): ?Division
    {
        return $user->division ?? $user->acceptedApplication?->division;
    }
}

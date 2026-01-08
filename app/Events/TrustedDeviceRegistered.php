<?php

namespace App\Events;

use App\Models\User;
use App\Models\TrustedDevice;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * TrustedDeviceRegistered Event
 * 
 * Fired when a new device is registered as trusted.
 * Can be used for security notifications to the user.
 */
class TrustedDeviceRegistered
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public TrustedDevice $trustedDevice;
    public ?string $ipAddress;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, TrustedDevice $trustedDevice, ?string $ipAddress = null)
    {
        $this->user = $user;
        $this->trustedDevice = $trustedDevice;
        $this->ipAddress = $ipAddress;
    }
}

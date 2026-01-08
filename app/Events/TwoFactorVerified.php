<?php

namespace App\Events;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/**
 * TwoFactorVerified Event
 * 
 * Fired when a user successfully verifies their two-factor authentication.
 * Can be used for logging successful authentications.
 */
class TwoFactorVerified
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public User $user;
    public ?string $ipAddress;
    public ?string $userAgent;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user, ?string $ipAddress = null, ?string $userAgent = null)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }
}

<?php

namespace App\Events\Admin;

use Faker\Core\Number;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PasswordPolicyUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $expiryDays;

    /**
     * Create a new event instance
     */
    public function __construct($expiryDays)
    {
        $this->expiryDays = $expiryDays;
    }
}

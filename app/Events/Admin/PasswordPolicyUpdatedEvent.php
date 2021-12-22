<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

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

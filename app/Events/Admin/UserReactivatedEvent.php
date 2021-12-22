<?php

namespace App\Events\Admin;

use App\Models\User;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class UserReactivatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $user;

    /**
     * Create a new event instance
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }
}

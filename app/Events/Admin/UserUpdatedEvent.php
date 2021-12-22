<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\User;

class UserUpdatedEvent
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

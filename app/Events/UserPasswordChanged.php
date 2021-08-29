<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\InteractsWithSockets;

use Illuminate\Foundation\Events\Dispatchable;
use App\Models\User;

class UserPasswordChanged
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}

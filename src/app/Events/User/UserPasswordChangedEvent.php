<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserPasswordChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is triggered when a user changes their password
     */
    public function __construct(public User $user) {}
}

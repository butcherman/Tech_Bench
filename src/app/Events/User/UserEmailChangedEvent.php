<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserEmailChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is triggered when a user email address is changed
     */
    public function __construct(public User $user, public string $originalEmail) {}
}

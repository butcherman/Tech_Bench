<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ResendWelcomeEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is triggered when an admin requests to re-send the welcome
     * email for a new user
     */
    public function __construct(public User $user) {}
}

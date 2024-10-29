<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserSettingsUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is triggered when User Settings are updated
     */
    public function __construct(public User $user) {}
}

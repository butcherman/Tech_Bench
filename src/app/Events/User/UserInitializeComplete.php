<?php

namespace App\Events\User;

use App\Models\UserInitialize;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UserInitializeComplete
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
    |---------------------------------------------------------------------------
    | Event is triggered when a new user has finished setting up their account.
    |---------------------------------------------------------------------------
    */
    public function __construct(public UserInitialize $token) {}
}

<?php

namespace App\Events\User;

use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class EmailChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $oldEmail;

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(string $oldEmail, User $user)
    {
        $this->oldEmail = $oldEmail;
        $this->user = $user;
    }
}

<?php

namespace App\Events\TechTip;

use App\Enums\CrudAction;
use App\Models\TechTip;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifiableTechTipEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public TechTip $techTip,
        public User $user,
        public CrudAction $action
    ) {}
}

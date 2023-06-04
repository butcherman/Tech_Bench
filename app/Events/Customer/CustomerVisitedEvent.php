<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerVisitedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, User $user)
    {
        $this->customer = $customer;
        $this->user = $user;
    }
}

<?php

namespace App\Events\Customer;

use App\Enum\CrudAction;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerContactEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Customer $customer,
        public CustomerContact $contact,
        public CrudAction $action
    ) {
        //
    }

    /**
     * Get the channels the event should broadcast on
     * 
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('customer.' . $this->customer->slug),
        ];
    }
}

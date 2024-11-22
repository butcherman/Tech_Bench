<?php

namespace App\Events\Customer;

use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerEquipmentDataFieldChanged
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
    |---------------------------------------------------------------------------
    | This event is triggered when a user changes a data field for a customer.
    | The event is broadcast to the customer equipment channel to notify other
    | users viewing the page that the data has been modified.
    |---------------------------------------------------------------------------
    */
    public function __construct(public Customer $customer) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        return [
            // TODO - Change to Customer Equipment Channel?
            new PrivateChannel('customer.'.$this->customer->slug),
        ];
    }
}

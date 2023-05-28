<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerContactCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
    public $contact;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerContact $contact)
    {
        $this->customer = $customer;
        $this->contact = $contact;
    }
}

<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerContact;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerContactUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    public $contact;

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerContact $contact, User $user)
    {
        $this->customer = $customer;
        $this->contact = $contact;
        $this->user = $user;
    }
}

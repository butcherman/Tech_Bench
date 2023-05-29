<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerEquipmentCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    public $equipment;

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerEquipment $equipment, User $user)
    {
        $this->customer = $customer;
        $this->equipment = $equipment;
        $this->user = $user;
    }
}

<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerEquipment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerEquipmentCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    public $equipment;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerEquipment $equipment)
    {
        $this->customer = $customer;
        $this->equipment = $equipment;
    }
}

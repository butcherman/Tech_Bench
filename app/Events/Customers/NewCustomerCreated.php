<?php

namespace App\Events\Customers;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;

class NewCustomerCreated
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $customer;

    /**
     * Create a new event instance
     */
    public function __construct(Customer $customer)
    {
        $this->customer = $customer;
    }
}

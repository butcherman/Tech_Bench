<?php

namespace App\Events\Customers\Contacts;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;
use App\Models\CustomerContact;

class CustomerContactDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $cust;
    public $cont;

    /**
     * Create a new event instance
     */
    public function __construct(Customer $cust, CustomerContact $cont)
    {
        $this->cust = $cust;
        $this->cont = $cont;
    }
}

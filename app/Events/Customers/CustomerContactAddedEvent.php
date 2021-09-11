<?php

namespace App\Events\Customers;

use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerContactAddedEvent
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

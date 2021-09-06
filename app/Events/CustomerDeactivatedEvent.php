<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;

class CustomerDeactivatedEvent
{
    use Dispatchable;
    use InteractsWithSockets;
    use SerializesModels;

    public $custData;

    /**
     * Create a new event instance
     */
    public function __construct(Customer $cust)
    {
        $this->custData = $cust;
    }
}

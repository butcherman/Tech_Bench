<?php

namespace App\Events\Customers;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CustomerLinkedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $added;
    public $cust_id;

    /**
     * Create a new event instance
     */
    public function __construct($cust_id, $added)
    {
        $this->added   = $added;
        $this->cust_id = $cust_id;
    }
}

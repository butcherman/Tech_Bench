<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;

class CustomerDetailsUpdated
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $details;
    public $cust_id;

    /**
     * Create a new event instance
     */
    public function __construct(Customer $details, $cust_id)
    {
        $this->details = $details;
        $this->cust_id = $cust_id;
    }
}

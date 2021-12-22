<?php

namespace App\Events\Customers\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;

class CustomerIdChangedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $cust;
    public $oldId;

    /**
     * Create a new event instance
     */
    public function __construct(Customer $cust, $oldId)
    {
        $this->cust  = $cust;
        $this->oldId = $oldId;
    }
}

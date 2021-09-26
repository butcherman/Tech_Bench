<?php

namespace App\Events\Customers\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;

class CustomerForceDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $cust;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $cust)
    {
        $this->cust = $cust;
    }
}

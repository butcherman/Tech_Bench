<?php

namespace App\Events\Customers\Files;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;
use App\Models\CustomerFile;

class CustomerFileForceDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $cust;
    public $file;

    /**
     * Create a new event instance
     */
    public function __construct(Customer $cust, CustomerFile $file)
    {
        $this->cust = $cust;
        $this->file = $file;
    }
}

<?php

namespace App\Events;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;
use App\Models\CustomerEquipment;

class CustomerEquipmentDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $cust;
    public $equip;

    /**
     * Create a new event instance
     */
    public function __construct(Customer $cust, CustomerEquipment $equip)
    {
        $this->cust  = $cust;
        $this->equip = $equip;
    }
}

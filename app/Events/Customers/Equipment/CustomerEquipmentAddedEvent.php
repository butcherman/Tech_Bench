<?php

namespace App\Events\Customers\Equipment;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;
use App\Models\CustomerEquipment;

class CustomerEquipmentAddedEvent
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

<?php

namespace App\Events\Equipment;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\EquipmentType;

class EquipmentTypeDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $equipmentType;

    /**
     * Create a new event instance
     */
    public function __construct(EquipmentType $equip)
    {
        $this->equipmentType = $equip;
    }
}

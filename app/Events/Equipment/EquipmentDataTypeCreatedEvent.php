<?php

namespace App\Events\Equipment;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\DataFieldType;

class EquipmentDataTypeCreatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $type;

    /**
     * Create a new event instance
     */
    public function __construct(DataFieldType $type)
    {
        $this->type = $type;
    }
}

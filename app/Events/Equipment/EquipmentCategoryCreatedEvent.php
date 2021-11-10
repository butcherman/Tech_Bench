<?php

namespace App\Events\Equipment;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\EquipmentCategory;

class EquipmentCategoryCreatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $category;

    /**
     * Create a new event instance
     */
    public function __construct(EquipmentCategory $category)
    {
        $this->category = $category;
    }
}

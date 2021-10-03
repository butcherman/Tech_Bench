<?php

namespace App\Events\Customers\Admin;

use App\Models\CustomerFileType;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class CustomerFileTypeUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $type;

    /**
     * Create a new event instance
     */
    public function __construct(CustomerFileType $type)
    {
        $this->type = $type;
    }
}

<?php

namespace App\Events\Customers\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\CustomerFileType;

class CustomerFileTypeDeletedEvent
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

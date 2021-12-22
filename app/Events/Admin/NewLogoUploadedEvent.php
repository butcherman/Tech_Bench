<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class NewLogoUploadedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $location;

    /**
     * Create a new event instance
     */
    public function __construct($location)
    {
        $this->location = $location;
    }
}

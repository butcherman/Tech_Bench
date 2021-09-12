<?php

namespace App\Events\Home;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ImageUploadedEvent
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

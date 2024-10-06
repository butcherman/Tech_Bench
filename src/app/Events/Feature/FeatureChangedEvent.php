<?php

namespace App\Events\Feature;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FeatureChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is Fired whenever a Feature is enabled or disabled
     */
    public function __construct() {}
}

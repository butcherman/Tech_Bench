<?php

namespace App\Events\Config;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UrlChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when the Tech Bench Public URL is changed
     */
    public function __construct(public string $newUrl, public string $oldUrl) {}
}

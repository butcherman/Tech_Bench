<?php

// TODO - Refactor

namespace App\Events\Config;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class UrlChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public string $newUrl, public string $oldUrl)
    {
        Log::debug('App URL Has Changed Event Triggered');
    }
}

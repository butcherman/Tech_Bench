<?php

namespace App\Events\Config;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

/*
|-------------------------------------------------------------------------------
| This event is called when an Administrator changes the public URL of the
| application.  This will force a rebuild of all JS files.
|-------------------------------------------------------------------------------
*/

class UrlChangedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public function __construct(public string $newUrl, public string $oldUrl) {}
}

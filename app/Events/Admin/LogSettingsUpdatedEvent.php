<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class LogSettingsUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $logSettings;

    /**
     * Create a new event instance
     */
    public function __construct($logSettings)
    {
        $this->logSettings = $logSettings;
    }
}

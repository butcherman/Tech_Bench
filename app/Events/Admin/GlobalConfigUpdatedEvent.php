<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Http\Requests\admin\SettingsRequest;

class GlobalConfigUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $settings;

    /**
     * Create a new event instance
     */
    public function __construct(SettingsRequest $settings)
    {
        $this->settings = $settings;
    }
}

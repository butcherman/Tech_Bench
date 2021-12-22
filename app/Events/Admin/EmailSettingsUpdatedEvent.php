<?php

namespace App\Events\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Http\Requests\Admin\EmailSettingsRequest;

class EmailSettingsUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $settings;

    /**
     * Create a new event instance
     */
    public function __construct(EmailSettingsRequest $settings)
    {
        $this->settings = $settings;
    }
}

<?php

namespace App\Events\TechTips;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\TechTip;

class TechTipUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $notify;
    public $techTip;

    /**
     * Create a new event instance
     */
    public function __construct(TechTip $techTip, $notify = false)
    {
        $this->techTip = $techTip;
        $this->notify  = $notify;
    }
}

<?php

namespace App\Events\TechTips;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\TechTip;

class TechTipForceDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $tip;

    /**
     * Create a new event instance.
     */
    public function __construct(TechTip $tip)
    {
        $this->tip = $tip;
    }
}

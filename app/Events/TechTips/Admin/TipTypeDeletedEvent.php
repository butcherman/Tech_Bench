<?php

namespace App\Events\TechTips\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\TechTipType;

class TipTypeDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $tipType;

    /**
     * Create a new event instance
     */
    public function __construct(TechTipType $type)
    {
        $this->tipType = $type;
    }
}

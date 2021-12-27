<?php

namespace App\Events\TechTips\Admin;

use Illuminate\Queue\SerializesModels;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TipTypeDeleteFailedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $error;

    /**
     * Create a new event instance
     */
    public function __construct(QueryException $error)
    {
        $this->error = $error;
    }
}

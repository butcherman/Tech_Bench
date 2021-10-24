<?php

namespace App\Events\TechTips;

use App\Models\TechTipComment;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

class TechTipCommentFlaggedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $comment;

    /**
     * Create a new event instance
     */
    public function __construct(TechTipComment $comment)
    {
        $this->comment = $comment;
    }
}

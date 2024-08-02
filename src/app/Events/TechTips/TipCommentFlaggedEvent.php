<?php

namespace App\Events\TechTips;

use App\Models\TechTipComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TipCommentFlaggedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TechTipComment $comment)
    {
        //
    }
}

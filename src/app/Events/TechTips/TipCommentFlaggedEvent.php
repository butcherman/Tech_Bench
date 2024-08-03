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
     * Event is triggered when a Tech Tip Comment is flagged as inappropriate
     */
    public function __construct(public TechTipComment $comment)
    {
        //
    }
}

<?php

namespace App\Events\TechTip;

use App\Models\TechTipComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TechTipCommentFlaggedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
    |---------------------------------------------------------------------------
    | Event is triggered when a Tech Tip Comment is flagged for review.
    |---------------------------------------------------------------------------
    */
    public function __construct(public TechTipComment $comment) {}
}

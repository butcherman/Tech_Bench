<?php

namespace App\Events\TechTip;

use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TechTipCommentFlaggedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TechTipComment $comment, public User $flaggedBy) {}
}

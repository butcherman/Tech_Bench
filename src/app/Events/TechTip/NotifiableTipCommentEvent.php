<?php

namespace App\Events\TechTip;

use App\Models\TechTipComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifiableTipCommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(public TechTipComment $comment) {}

    /**
     * Get the channels the event should broadcast on
     *
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel($this->comment->TechTip->slug),
        ];
    }
}

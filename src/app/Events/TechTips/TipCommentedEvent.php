<?php

namespace App\Events\TechTips;

use App\Models\TechTipComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TipCommentedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when a Tech Tip is commented on
     */
    public function __construct(public TechTipComment $comment) {}

    /**
     * Get the channels the event should broadcast on
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tech-tips.'.$this->comment->tip_id),
        ];
    }

    public function broadcastAs(): string
    {
        return 'TechTipCommentEvent';
    }
}

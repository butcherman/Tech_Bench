<?php

namespace App\Events\TechTips;

use App\Models\TechTipComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TipCommentedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when a Tech Tip is commented on
     */
    public function __construct(public TechTipComment $comment)
    {
        //
    }

    /**
     * Get the channels the event should broadcast on
     * TODO - Broadcast this event
     */
    // public function broadcastOn(): array
    // {
    //     return [
    //         new PrivateChannel('channel-name'),
    //     ];
    // }
}
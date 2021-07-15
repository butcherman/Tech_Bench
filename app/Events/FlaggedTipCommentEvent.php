<?php

namespace App\Events;

use App\Models\TechTipComment;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FlaggedTipCommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $comment;
    public $flaggedBy;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TechTipComment $comment, User $flaggedBy)
    {
        $this->comment   = $comment;
        $this->flaggedBy = $flaggedBy;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new PrivateChannel('channel-name');
    // }
}

<?php

namespace App\Events\TechTips;

use App\Models\TechTipComment;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TipCommentedEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is fired when a Tech Tip is commented on
     */
    public function __construct(public TechTipComment $comment)
    {
        Log::debug('Tech Tip Comment Event Called', $comment->toArray());
    }

    /**
     * Get the channels the event should broadcast on
     */
    public function broadcastOn(): array
    {
        Log::debug('Broadcasting Tech Tip Event on tech-tips channel ' .
            $this->comment->tip_id);

        return [
            new PrivateChannel('tech-tips.' . $this->comment->tip_id),
        ];
    }

    public function broadcastAs()
    {
        return 'TechTipCommentEvent';
    }
}

<?php

namespace App\Events\TechTip;

use App\Enums\CrudAction;
use App\Models\TechTipComment;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotifiableCommentEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /*
    |---------------------------------------------------------------------------
    | Event is triggered when someone comments on a Tech Tip.
    |---------------------------------------------------------------------------
    */
    public function __construct(
        public TechTipComment $comment,
        public CrudAction $action
    ) {}

    /**
     * Get the channels the event should broadcast on.
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

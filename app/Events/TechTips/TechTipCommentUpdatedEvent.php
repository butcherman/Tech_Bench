<?php

namespace App\Events\TechTips;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\TechTipComment;

class TechTipCommentUpdatedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $comment;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(TechTipComment $comment)
    {
        $this->comment = $comment;
    }
}

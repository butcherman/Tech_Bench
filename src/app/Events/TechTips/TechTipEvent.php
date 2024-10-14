<?php

namespace App\Events\TechTips;

use App\Enum\CrudAction;
use App\Models\TechTip;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TechTipEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Event is triggered on Created or Updated Tech Tip
     */
    public function __construct(
        public TechTip $techTip,
        public CrudAction $action,
        public bool $sendNotification
    ) {}

    public function broadcastOn(): array
    {
        return [
            new PrivateChannel('tech-tips.'.$this->techTip->tip_id),
        ];
    }

    /**
     * Since the model includes files, we will only broadcast some data
     */
    public function broadcastWith(): array
    {
        return [
            'tip_id' => $this->techTip->tip_id,
            'subject' => $this->techTip->subject,
            'user_id' => $this->techTip->user_id,
            'updated_id' => $this->techTip->updated_id,
        ];
    }

    public function broadcastAs(): string
    {
        return 'TechTipEvent';
    }
}

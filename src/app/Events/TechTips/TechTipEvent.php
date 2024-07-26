<?php

namespace App\Events\TechTips;

use App\Enum\CrudAction;
use App\Models\TechTip;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TechTipEvent // implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Create a new event instance.
     */
    public function __construct(public TechTip $techTip, CrudAction $action, public bool $sendNotification)
    {
        $this->action = $action->name;

        Log::debug('Tech Tip Event called', [
            'tip_data' => $this->techTip->toArray(),
            'crud_action' => $this->action,
        ]);
    }

    /**
     * Broadcast on Tech Tip Channel
     */
    public function broadcastOn(): array
    {
        Log::debug('Broadcasting Tech Tip Event on tech-tips channel');

        return [
            new PrivateChannel('tech-tips'),
        ];
    }
}

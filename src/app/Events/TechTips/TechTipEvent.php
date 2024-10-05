<?php
// TODO - Refactor
namespace App\Events\TechTips;

use App\Enum\CrudAction;
use App\Models\TechTip;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class TechTipEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Event is triggered on Created or Updated Tech Tip
     */
    public function __construct(
        public TechTip $techTip,
        CrudAction $action,
        public bool $sendNotification
    ) {
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
        Log::debug('Broadcasting Tech Tip Event on tech-tips channel '.
            $this->techTip->tip_id);

        return [
            new PrivateChannel('tech-tips.'.$this->techTip->tip_id),
        ];
    }

    /**
     * Since the model includes files, we will only broadcast some data
     */
    public function broadcastWith()
    {
        return [
            'tip_id' => $this->techTip->tip_id,
            'subject' => $this->techTip->subject,
            'user_id' => $this->techTip->user_id,
            'updated_id' => $this->techTip->updated_id,
        ];
    }

    public function broadcastAs()
    {
        return 'TechTipEvent';
    }
}

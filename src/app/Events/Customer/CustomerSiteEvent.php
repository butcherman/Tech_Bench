<?php

namespace App\Events\Customer;

use App\Enum\CrudAction;
use App\Models\Customer;
use App\Models\CustomerSite;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerSiteEvent // implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public Customer $customer,
        public CustomerSite $site,
        CrudAction $action
    ) {
        $this->action = $action->name;

        Log::debug('Customer Site Event called', [
            'customer' => $customer->toArray(),
            'site' => $site->toArray(),
            'crud_action' => $action->name,
        ]);
    }

    /**
     * Get the channels the event should broadcast on
     *
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        Log::debug('Broadcasting Customer Site Event on channel `customer.' .
            $this->customer->slug . '`');

        return [
            new PrivateChannel('customer.' . $this->customer->slug),
        ];
    }
}

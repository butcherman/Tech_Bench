<?php

namespace App\Events\Customer;

use App\Enum\CrudAction;
use App\Models\Customer;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Event is triggered when Customer is Created, Updated, or Destroyed
     */
    public function __construct(public Customer $customer, CrudAction $action)
    {
        $this->action = $action->name;

        Log::debug('Customer Event called', [
            'customer' => $customer->toArray(),
            'crud_action' => $action->name
        ]);
    }

    /**
     * Get the channels the event should broadcast on
     *
     * @codeCoverageIgnore
     */
    public function broadcastOn(): array
    {
        Log::debug('Broadcasting Customer Event on channel `customer.' .
            $this->customer->slug . '`');

        return [
            new PrivateChannel('customer.' . $this->customer->slug),
        ];
    }
}
<?php

// TODO - Refactor

namespace App\Events\Customer;

use App\Enum\CrudAction;
use App\Models\Customer;
use App\Models\CustomerAlert;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerAlertEvent // implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Event is triggered when a Customer Alert is Created, Updated, or Destroyed
     */
    public function __construct(
        public Customer $customer,
        public CustomerAlert $alert,
        CrudAction $action
    ) {
        $this->action = $action->name;

        Log::debug('Customer Alert Event called', [
            'customer' => $customer->toArray(),
            'alert' => $alert->toArray(),
            'crud_action' => $action->name,
        ]);
    }

    /**
     * Get the channels the event should broadcast on
     */
    public function broadcastOn(): array
    {
        Log::debug('Broadcasting Customer Alert Event on channel `customer.'.
            $this->customer->slug.'`');

        return [
            new PrivateChannel('customer.'.$this->customer->slug),
        ];
    }
}

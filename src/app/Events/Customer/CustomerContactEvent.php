<?php

namespace App\Events\Customer;

use App\Enum\CrudAction;
use App\Models\Customer;
use App\Models\CustomerContact;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class CustomerContactEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $action;

    /**
     * Event is triggered when Customer Contact is Created, Updated, or Destroyed
     */
    public function __construct(
        public Customer $customer,
        public CustomerContact $contact,
        CrudAction $action
    ) {
        $this->action = $action->name;

        Log::debug('Customer Contact Event called', [
            'customer' => $customer->toArray(),
            'contact' => $contact->toArray(),
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
        Log::debug('Broadcasting Customer Contact Event on channel `customer.' .
            $this->customer->slug . '`');

        return [
            new PrivateChannel('customer.' . $this->customer->slug),
        ];
    }
}

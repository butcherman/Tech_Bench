<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerNote;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerNoteUpdatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;
    public $note;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerNote $note)
    {
        $this->customer = $customer;
        $this->note = $note;
    }
}

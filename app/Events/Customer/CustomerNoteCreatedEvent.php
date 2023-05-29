<?php

namespace App\Events\Customer;

use App\Models\Customer;
use App\Models\CustomerNote;
use App\Models\User;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CustomerNoteCreatedEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $customer;

    public $note;

    public $user;

    /**
     * Create a new event instance.
     */
    public function __construct(Customer $customer, CustomerNote $note, User $user)
    {
        $this->customer = $customer;
        $this->note = $note;
        $this->user = $user;
    }
}

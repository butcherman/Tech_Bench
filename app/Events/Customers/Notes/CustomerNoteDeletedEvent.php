<?php

namespace App\Events\Customers\Notes;

use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;

use App\Models\Customer;
use App\Models\CustomerNote;

class CustomerNoteDeletedEvent
{
    use Dispatchable;
    use SerializesModels;
    use InteractsWithSockets;

    public $cust;
    public $note;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Customer $cust, CustomerNote $note)
    {
        $this->cust = $cust;
        $this->note = $note;
    }
}

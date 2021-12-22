<?php

namespace App\Listeners\Customers\Notes;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Notes\CustomerNoteForceDeletedEvent;

class LogForceDeletedCustomerNote
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event
     */
    public function handle(CustomerNoteForceDeletedEvent $event)
    {
        Log::channel('cust')->info('Customer note has been permanently deleted by '.Auth::user()->username.' for Customer '.$event->cust->name.'.  Details, ', [
            'cust_id' => $event->cust->cust_id,
            'note_id' => $event->note->note_id,
            'subject' => $event->note->subject,
        ]);
    }
}

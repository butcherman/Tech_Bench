<?php

namespace App\Listeners\Customers\Notes;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Notes\CustomerNoteDeletedEvent;

class LogDeletedNote
{
    /**
     * Handle the event
     */
    public function handle(CustomerNoteDeletedEvent $event)
    {
        Log::channel('cust')->info('Customer note has been deleted by '.Auth::user()->username.' for Customer '.$event->cust->name.'.  Details, ', [
            'cust_id' => $event->cust->cust_id,
            'note_id' => $event->note->note_id,
            'subject' => $event->note->subject,
        ]);
    }
}

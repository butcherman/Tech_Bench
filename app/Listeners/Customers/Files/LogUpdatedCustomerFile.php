<?php

namespace App\Listeners\Customers\Files;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Events\Customers\Files\CustomerFileUpdatedEvent;

class LogUpdatedCustomerFile
{
    /**
     * Handle the event
     */
    public function handle(CustomerFileUpdatedEvent $event)
    {
        Log::channel('cust')->info('File has been updated for '.$event->cust->name.' by '.Auth::user()->username.'.  Details - ', [
            'cust_id'   => $event->cust->cust_id,
            'file_id'   => $event->file->file_id,
            'file_name' => $event->file->name,
        ]);
    }
}

<?php

namespace App\Listeners\Customers\Admin;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

use App\Events\Customers\Admin\CustomerFileTypeDeletedErrorEvent;

class LogFileTypeDeletedError
{
    /**
     * Handle the event
     */
    public function handle(CustomerFileTypeDeletedErrorEvent $event)
    {
        Log::channel('cust')->error('Deletion of Customer File Type by '.Auth::user()->username.' failed.  Details - ', [
            'file_type' => $event->fileType->toArray(),
            'error'     => $event->error,
        ]);
    }
}

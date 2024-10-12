<?php

namespace App\Observers;

use App\Models\CustomerContact;
use Illuminate\Support\Facades\Log;

class CustomerContactObserver
{
    public function created(CustomerContact $contact)
    {
        Log::info(
            'New Customer Contact created for '.$contact->Customer->name.' by '.
                request()->user()->username,
            $contact->toArray()
        );
    }

    public function updated(CustomerContact $contact)
    {
        Log::info(
            'Customer Contact updated by '.request()->user()->username,
            $contact->load('Customer')->toArray()
        );
    }

    public function deleted(CustomerContact $contact)
    {
        Log::notice(
            'Customer Contact soft-deleted for '.
                $contact->Customer->name.' by '.request()->user()->username,
            $contact->toArray()
        );
    }

    public function restored(CustomerContact $contact)
    {
        Log::info(
            'Customer Contact restored for '.$contact->Customer->name.' by '.
                request()->user()->username,
            $contact->toArray()
        );
    }

    public function forceDeleted(CustomerContact $contact)
    {
        Log::notice(
            'Customer Contact force deleted for '.$contact->Customer->name.
                ' by '.request()->user()->username,
            $contact->toArray()
        );
    }
}

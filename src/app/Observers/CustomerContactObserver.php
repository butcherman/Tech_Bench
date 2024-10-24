<?php

namespace App\Observers;

use App\Models\CustomerContact;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class CustomerContactObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }

    public function created(CustomerContact $contact): void
    {
        Log::info(
            'New Customer Contact created for '.$contact->Customer->name.' by '.
                $this->user,
            $contact->toArray()
        );
    }

    public function updated(CustomerContact $contact): void
    {
        Log::info(
            'Customer Contact updated by '.$this->user,
            $contact->load('Customer')->toArray()
        );
    }

    public function deleted(CustomerContact $contact): void
    {
        Log::notice(
            'Customer Contact soft-deleted for '.
                $contact->Customer->name.' by '.$this->user,
            $contact->toArray()
        );
    }

    public function restored(CustomerContact $contact): void
    {
        Log::info(
            'Customer Contact restored for '.$contact->Customer->name.' by '.
                $this->user,
            $contact->toArray()
        );
    }

    public function forceDeleting(CustomerContact $contact): void
    {
        Log::notice(
            'Customer Contact force deleted for '.$contact->Customer->name.
                ' by '.$this->user,
            $contact->toArray()
        );
    }
}

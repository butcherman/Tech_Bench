<?php

namespace App\Observers;

use App\Models\CustomerSite;
use Illuminate\Support\Facades\Log;

class CustomerSiteObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            ! is_null(request()->user()) => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            default => request()->ip(),
        };
    }

    public function created(CustomerSite $site): void
    {
        Log::info(
            'New Customer Site created for '.$site->Customer.' by '.$this->user,
            $site->toArray()
        );
    }

    public function updated(CustomerSite $site): void
    {
        Log::info(
            'Customer Site '.$site->site_name.' updated for '.
                $site->Customer->name.' by '.$this->user,
            $site->toArray()
        );
    }

    public function deleted(CustomerSite $site): void
    {
        Log::alert('Customer Site '.$site->site_name.' for '.
            $site->Customer->name.' has been disabled by '.$this->user);
    }

    public function restored(CustomerSite $site): void
    {
        //
    }

    public function forceDeleted(CustomerSite $site): void
    {
        Log::alert('Customer Site '.$site->site_name.' for '.
            $site->Customer->name.' has been trashed by '.$this->user);
    }
}

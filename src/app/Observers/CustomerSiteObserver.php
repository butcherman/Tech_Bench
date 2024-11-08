<?php

namespace App\Observers;

use App\Models\CustomerSite;
use Illuminate\Support\Facades\Log;

class CustomerSiteObserver extends Observer
{
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
        Log::alert(
            'Customer Site '.$site->site_name.' has been disabled by '.
                $this->user
        );
    }

    // public function restored(CustomerSite $site): void
    // {
    //     //
    // }

    public function forceDeleted(CustomerSite $site): void
    {
        Log::alert(
            'Customer Site '.$site->site_name.' has been trashed by '.$this->user
        );
    }
}

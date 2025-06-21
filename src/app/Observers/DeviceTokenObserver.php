<?php

namespace App\Observers;

use App\Models\DeviceToken;
use Illuminate\Support\Facades\Log;

class DeviceTokenObserver extends Observer
{
    /**
     * Handle the DeviceToken "created" event.
     */
    public function created(DeviceToken $deviceToken): void
    {
        Log::info(
            'New Device Token created for '.$deviceToken->user->username.' by '.
                $this->user,
            $deviceToken->toArray()
        );
    }

    /**
     * Handle the DeviceToken "updated" event.
     */
    public function updated(DeviceToken $deviceToken): void
    {
        Log::debug(
            'Device Token updated for '.$deviceToken->user->username.' by '.
                $this->user,
            $deviceToken->toArray()
        );
    }

    /**
     * Handle the DeviceToken "deleted" event.
     */
    public function deleted(DeviceToken $deviceToken): void
    {
        Log::info(
            'Device Token deleted for '.$deviceToken->user->username.' by '.
                $this->user,
            $deviceToken->toArray()
        );
    }
}

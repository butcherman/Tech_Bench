<?php

namespace App\Observers;

use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class DeviceTokenObserver
{
    /** @var User|string */
    protected $user;

    public function __construct()
    {
        $this->user = match (true) {
            request()->user() !== null => request()->user()->username,
            request()->ip() === '127.0.0.1' => 'Internal Service',
            // @codeCoverageIgnoreStart
            default => request()->ip(),
            // @codeCoverageIgnoreEnd
        };
    }

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

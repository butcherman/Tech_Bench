<?php

namespace App\Listeners\User;

use App\Events\User\UserSettingsUpdatedEvent;
use Illuminate\Support\Facades\Log;

class HandleUserSettingsUpdatedListener
{
    /**
     * Handle the event.
     *
     * @codeCoverageIgnore
     */
    public function handle(UserSettingsUpdatedEvent $event): void
    {
        Log::info(
            'User Settings for '.$event->user->username.' have been updated by '.
                request()->user()->username
        );
    }
}

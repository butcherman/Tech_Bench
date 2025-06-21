<?php

namespace App\Observers;

use App\Models\UserInitialize;
use Illuminate\Support\Facades\Log;

class UserInitializeObserver extends Observer
{
    /**
     * Handle the UserInitialize "created" event.
     */
    public function created(UserInitialize $userInitialize): void
    {
        Log::info(
            'New User Initialization link created for '.$userInitialize->username.
                ' by '.$this->user
        );
    }

    /**
     * Handle the UserInitialize "updated" event.
     */
    public function updated(UserInitialize $userInitialize): void
    {
        Log::info(
            'User Initialization link updated for '.$userInitialize->username.
                ' by '.$this->user
        );
    }

    /**
     * Handle the UserInitialize "deleted" event.
     */
    public function deleted(UserInitialize $userInitialize): void
    {
        Log::info(
            'User Initialization link deleted for '.$userInitialize->username.
                ' by '.$this->user
        );
    }
}

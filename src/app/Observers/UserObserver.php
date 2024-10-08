<?php

namespace App\Observers;

use App\Jobs\User\CreateUserSettingsEntriesJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Support\Facades\Log;

class UserObserver
{
    /**
     * Handle User Created Event
     */
    public function created(User $user): void
    {
        Log::stack(['daily', 'auth'])
            ->notice(
                'New User created by '.request()->user()->username,
                $user->toArray()
            );

        dispatch(new SendWelcomeEmailJob($user));
        dispatch(new CreateUserSettingsEntriesJob($user));
    }

    /**
     * Handle User Updated Event
     */
    public function updated(User $user): void
    {
        if (! request()->user()) {
            Log::stack(['daily', 'auth'])
                ->notice('User '.$user->username.' has reset their forgotten password');
        } else {
            Log::stack(['daily', 'auth'])
                ->info('User information for '.$user->username.' has been updated by '.
                request()->user()->username, $user->toArray());
        }
    }

    /**
     * Handle User Soft Deleted Event.
     */
    public function deleted(User $user): void
    {
        Log::stack(['daily', 'auth'])
            ->notice(
                'User '.$user->username.' has been deactivated by '.
                request()->user()->username
            );
    }

    /**
     * Handle User Restored Event
     */
    public function restored(User $user): void
    {
        Log::stack(['daily', 'auth'])
            ->notice(
                'User '.$user->username.' has been reactivated by '.request()->user()->username
            );
    }
}

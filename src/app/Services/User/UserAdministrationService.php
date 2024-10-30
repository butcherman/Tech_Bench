<?php

namespace App\Services\User;

use App\Jobs\User\CreateUserSettingsJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UserAdministrationService
{
    /**
     * Return a list of active, or deactivated users
     */
    public function getAllUsers(?bool $trashed = false): Collection|Builder
    {
        if ($trashed) {
            return User::onlyTrashed();
        }

        return User::all();
    }

    /**
     * Create a new user
     */
    public function createUser(Collection $requestData, ?bool $noEmail = false): User
    {
        $newUser = User::create($requestData->all());

        // Send welcome email, unless forcefully bypassed
        if (! $noEmail) {
            dispatch(new SendWelcomeEmailJob($newUser));
        }

        // Build the Users Settings Data Entries
        dispatch(new CreateUserSettingsJob($newUser));

        return $newUser;
    }
}

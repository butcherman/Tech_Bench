<?php

namespace App\Services\User;

use App\Jobs\User\CreateUserSettingsJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Support\Collection;

class UserAdministrationService
{
    /*
    |---------------------------------------------------------------------------
    | Users
    |---------------------------------------------------------------------------
    */

    /**
     * Return a list of active, or deactivated users
     */
    public function getAllUsers(?bool $trashed = false): EloquentCollection
    {
        if ($trashed) {
            return User::onlyTrashed()->get()->makeVisible('deleted_at');
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

    /**
     * Update an existing user
     */
    public function updateUser(Collection $requestData, User $user): User
    {
        $user->update($requestData->all());

        return $user->fresh();
    }

    /**
     * Disable a user
     */
    public function disableUser(User $user): void
    {
        $user->delete();
    }

    /**
     * Restore a soft deleted/disabled user
     */
    public function restoreUser(User $user): void
    {
        $user->restore();
    }

    /*
    |---------------------------------------------------------------------------
    | User Security - Multi-Factor Auth & Passwords
    |---------------------------------------------------------------------------
    */

    /**
     * Clear the Two Factor settings for a single user
     */
    public function clearTwoFaSettings(User $user): void
    {
        // Delete 2FA Settings
        $user->two_factor_secret = null;
        $user->two_factor_recovery_codes = null;
        $user->two_factor_confirmed_at = null;
        $user->two_factor_via = null;

        $user->save();

        // Delete saved devices
        $user->DeviceTokens()->delete();
    }

    /**
     * Update a users Password Expire date
     */
    public function updatePasswordExpire(User $user): void
    {
        $newExpireDate = $user->getNewExpireTime();

        // If the new expire date is null, remove the expire date altogether
        if (is_null($newExpireDate)) {
            // If the current expire date is greater than today, null it out
            if ($user->password_expires > now()) {
                $user->password_expires = null;
                $user->save();
            }

            return;
        }

        // If the new expire date is less than the current expire, update it
        if ($newExpireDate < $user->password_expires || is_null($user->password_expires)) {
            $user->password_expires = $newExpireDate;
            $user->save();
        }
    }
}

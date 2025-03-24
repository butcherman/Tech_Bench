<?php

namespace App\Services\User;

use App\Events\Feature\FeatureChangedEvent;
use App\Facades\DbException;
use App\Jobs\User\CreateUserSettingsJob;
use App\Jobs\User\SendWelcomeEmailJob;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
use Illuminate\Database\QueryException;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;

class UserAdministrationService
{
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
     * Return a list of users with Installer Level Role.
     */
    public function getInstallerUsers(): EloquentCollection
    {
        return User::whereRoleId(1)
            ->get(['user_id', 'first_name', 'last_name'])
            ->makeHidden(['initials', 'role_name', 'full_name'])
            ->makeVisible('user_id');
    }

    /**
     * Return a list of user with any type of Admin Access
     */
    public function getAdminUsers(): EloquentCollection
    {
        $roleList = UserRole::whereHas('UserRolePermission', function ($q) {
            $q->where('allow', true)
                ->whereHas('UserRolePermissionType', function ($q2) {
                    $q2->where('is_admin_link', true);
                });
        })->get()->pluck('role_id');

        return User::whereIn('role_id', $roleList)
            ->get(['user_id', 'first_name', 'last_name'])
            ->makeHidden(['initials', 'role_name', 'full_name'])
            ->makeVisible('user_id');
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

        event(new FeatureChangedEvent($user));

        return $user->fresh();
    }

    /**
     * Disable/Delete a user
     */
    public function destroyUser(User $user, bool $force = false): void
    {
        if ($force) {
            try {
                $user->forceDelete();
            } catch (QueryException $e) {
                DbException::check($e, 'This User has contributions tied to them.  Unable to delete.');
            }

            return;
        }

        $user->delete();
    }

    /**
     * Restore a soft deleted/disabled user
     */
    public function restoreUser(User $user): void
    {
        $user->restore();
    }

    /**
     * Update a users Password Expire date
     */
    public function resetPasswordExpire(User $user): void
    {
        $newExpireDate = $user->getNewExpireTime();
        Log::debug('New Expiration Date for ' . $user->username . ' - ' . $newExpireDate);

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

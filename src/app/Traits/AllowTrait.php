<?php

namespace App\Traits;

use App\Models\User;
use App\Models\UserRolePermission;
use Illuminate\Support\Facades\Log;

/**
 *  AllowTrait only has one function to check permission for the policies
 */
trait AllowTrait
{
    protected function checkPermission(User $user, $description)
    {
        $allowed = UserRolePermission::whereRoleId($user->role_id)
            ->whereHas('UserRolePermissionTypes', function ($q) use ($description) {
                $q->where('description', $description);
            })
            ->first();

        Log::channel('auth')->debug('User '.$user->username.' is trying to get permission for '.$description.'.  Result - '.$allowed);
        if ($allowed) {
            return $allowed->allow;
        }

        // @codeCoverageIgnoreStart
        return false;
        // @codeCoverageIgnoreEnd
    }
}

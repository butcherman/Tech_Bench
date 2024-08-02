<?php

namespace App\Features;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Support\Facades\Log;

class TechTipComment
{
    /**
     * Determine if users are able to comment on Tech Tips
     */
    public function resolve(User $user): mixed
    {
        Log::stack(['tips', 'daily'])
            ->debug('Checking status of Feature - Comment on Tech Tip');

        // If feature is disabled, just return false
        $isEnabled = (bool) config('techTips.allow_comments');
        if (! $isEnabled) {
            Log::stack(['tips', 'daily'])
                ->debug('Feature - Comment on Tech Tip - is Disabled');

            return false;
        }

        Log::stack(['tips', 'daily'])
            ->debug('Feature Enabled - Checking if user can use this feature');
        $permType = UserRolePermissionType::where('description', 'Comment on Tech Tip')
            ->first();
        $allowed = UserRolePermission::where('role_id', $user->role_id)
            ->where('perm_type_id', $permType->perm_type_id)
            ->first()->allow;

        Log::stack(['tips', 'daily'])
            ->debug('User '.$user->username.' is '.
                $allowed ? 'allowed' : 'not allowed'.
                'to use this feature');

        return $allowed;
    }
}

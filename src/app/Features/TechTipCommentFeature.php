<?php

namespace App\Features;

use App\Models\User;
use App\Models\UserRolePermission;
use App\Models\UserRolePermissionType;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Lottery;

class TechTipCommentFeature
{
    /**
     * This Feature allows users to comment on Tech Tips
     */
    public function resolve(): mixed
    {
        Log::stack(['tip', 'daily'])
            ->debug('Checking status of Feature - Comment on Tech Tip');

        // If feature is disabled, return false
        $isEnabled = (bool) config('techTips.allow_comments');
        if (!$isEnabled) {
            Log::stack(['tips', 'daily'])
                ->debug('Feature - Comment on Tech Tip - is Disabled');

            return false;
        }

        return true;
    }
}

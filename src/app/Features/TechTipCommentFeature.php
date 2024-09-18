<?php

namespace App\Features;

use Illuminate\Support\Facades\Log;

class TechTipCommentFeature
{
    /**
     * This Feature allows users to comment on Tech Tips
     */
    public function resolve(): bool
    {
        Log::debug('Checking status of Feature - Comment on Tech Tip');

        // If feature is disabled, return false
        $isEnabled = (bool) config('techTips.allow_comments');
        if (! $isEnabled) {
            Log::debug('Feature - Comment on Tech Tip - is Disabled');

            return false;
        }

        return true;
    }
}

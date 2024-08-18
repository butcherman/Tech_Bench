<?php

namespace App\Features;

use Illuminate\Support\Facades\Log;

class PublicTechTipFeature
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(): bool
    {
        Log::stack(['tip', 'daily'])
            ->debug('Checking status of Feature - Public Tech Tip');

        // Check if feature is enabled in config settings
        $isEnabled = (bool) config('techTips.allow_public');

        return $isEnabled;
    }
}

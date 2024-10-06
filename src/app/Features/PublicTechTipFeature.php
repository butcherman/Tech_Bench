<?php

// TODO - Refactor

namespace App\Features;

use Illuminate\Support\Facades\Log;

class PublicTechTipFeature
{
    /**
     * Resolve the feature's initial value.
     */
    public function resolve(): bool
    {
        Log::debug('Checking status of Feature - Public Tech Tip');

        // Check if feature is enabled in config settings
        $isEnabled = (bool) config('tech-tips.allow_public');

        return $isEnabled;
    }
}

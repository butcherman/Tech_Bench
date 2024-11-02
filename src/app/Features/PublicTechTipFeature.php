<?php

namespace App\Features;

class PublicTechTipFeature
{
    /**
     * Determine if Public Tech Tips are enabled
     */
    public function resolve(): mixed
    {
        return config('tech-tips.allow_public');
    }
}

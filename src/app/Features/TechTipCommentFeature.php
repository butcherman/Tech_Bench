<?php

namespace App\Features;

class TechTipCommentFeature
{
    /**
     * Check if Tech Tip Comment Feature is enabled.
     */
    public function resolve(): mixed
    {
        return config('tech-tips.allow_comments');
    }
}

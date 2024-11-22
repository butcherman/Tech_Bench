<?php

namespace App\Features;

class TechTipCommentFeature
{
    /*
    |---------------------------------------------------------------------------
    | Tech Tip Comment Feature allows users to comment on Tech Tips to add
    | additional information that may not be in the tip.
    |---------------------------------------------------------------------------
    */
    public function resolve(): mixed
    {
        return config('tech-tips.allow_comments');
    }
}

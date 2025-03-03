<?php

namespace App\Features;

/*
|-------------------------------------------------------------------------------
| Tech Tip Comment Feature allows users to comment on Tech Tips to add
| additional information that may not be in the tip.
|-------------------------------------------------------------------------------
*/

class TechTipCommentFeature
{
    public function resolve(): mixed
    {
        return config('tech-tips.allow_comments');
    }
}

<?php

namespace App\Features;

/*
|-------------------------------------------------------------------------------
| Public Tech Tip Feature creates a public knowledge base for unregistered
| users to access.
|-------------------------------------------------------------------------------
*/

class PublicTechTipFeature
{
    public function resolve(): bool
    {
        return config('tech-tips.allow_public');
    }
}

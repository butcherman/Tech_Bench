<?php

namespace App\Features;

class PublicTechTipFeature
{
    /*
    |---------------------------------------------------------------------------
    | Public Tech Tip Feature creates a public knowledge base for unregistered
    | users to access.
    |---------------------------------------------------------------------------
    */
    public function resolve(): mixed
    {
        return config('tech-tips.allow_public');
    }
}

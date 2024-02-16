<?php

namespace App\Traits;

use App\Models\UserPinnedLink;

/**
 * Pinned Link Trait allows the Controller to interact with the UserPinnedLink Model
 */
trait PinnedLinkTrait
{
    public function isPinnedLink(string $pinName, string $modelName, int $user_id)
    {
        return (bool) UserPinnedLink::where('pin_name', $pinName)
            ->where('model_name', $modelName)
            ->where('user_id', $user_id)
            ->count();
    }
}
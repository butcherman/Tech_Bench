<?php

namespace App\Traits\Models;

use App\Models\User;

trait HasRecents
{
    /**
     * Update the Users Recent visits activity
     */
    public function touchRecent(User $user): void
    {
        $isRecent = $this->Recent->where('user_id', $user->user_id)->first();

        if ($isRecent) {
            $isRecent->pivot->touch();
        } else {
            $this->Recent()->attach($user);
        }
    }
}

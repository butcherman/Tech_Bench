<?php

namespace App\Traits\Models;

use App\Models\User;

trait HasBookmarks
{
    /**
     * Determine if the user requesting the page has bookmarked customer
     */
    public function isFav(User $user)
    {
        $bookmarks = $this->Bookmarks->pluck('user_id')->toArray();

        return in_array($user->user_id, $bookmarks);
    }

    /**
     * Attach or Detach a user's bookmark
     */
    public function toggleBookmark(User $user, bool $value): void
    {
        if ($value) {
            $this->Bookmarks()->attach($user);
        } else {
            $this->Bookmarks()->detach($user);
        }
    }
}

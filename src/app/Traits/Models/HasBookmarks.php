<?php

namespace App\Traits\Models;

use App\Models\User;

trait HasBookmarks
{
    /**
     * Determine if the user requesting the page has bookmarked customer
     */
    public function isFav(User $user): bool
    {
        $bookmark = $this->Bookmarks->where('user_id', $user->user_id)->first();

        return $bookmark ? true : false;
    }

    /**
     * Attach or Detach a user's bookmark
     */
    public function toggleBookmark(User $user, bool $set): void
    {
        if ($set) {
            $this->Bookmarks()->attach($user);
        } else {
            $this->Bookmarks()->detach($user);
        }
    }
}

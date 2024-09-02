<?php

use App\Features\FileLinkFeature;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/**
 * User Notification Channel
 */
Broadcast::channel('App.Models.User.{id}', function (User $user, int $id) {
    Log::debug('User ' . $user->username . ' connecting to Notification Broadcast Channel');
    return (int) $user->user_id === (int) $id;
});

/**
 * Active Tech Tip Channel
 */
Broadcast::channel('tech-tips.{tip_id}', function (User $user, int $tip_id) {
    Log::debug('User ' . $user->username . ' registering to Tech Tip Channel for Tip ' . $tip_id);
    return $user ? true : false;
});

/**
 * Active File Link Channel
 */
Broadcast::channel('file-link.{link_id}', function (User $user, int $link_id) {
    Log::debug('User ' . $user->username . ' registering to File Link Channel for link ID ' . $link_id);
    if (!config('fileLink.feature_enabled'))
        return false;

    return $user->features()->active(FileLinkFeature::class);
});

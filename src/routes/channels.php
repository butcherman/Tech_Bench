<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

/**
 * Notification Channel
 */
Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::debug('User ' . $user->username . ' connecting to Notification Broadcast Channel');
    return (int) $user->user_id === (int) $id;
});

/**
 * Active Tech Tip Channel
 */
Broadcast::channel('tech-tips.{tip_id}', function ($user, $tip_id) {
    Log::debug('User ' . $user->username . ' registering to Tech Tip Channel for Tip ' . $tip_id);
    return $user ? true : false;
});

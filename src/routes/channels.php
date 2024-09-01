<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    Log::debug('User ' . $user->username . ' connecting to Notification Broadcast Channel');
    return (int) $user->user_id === (int) $id;
});

<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;

Broadcast::channel('public', function() {
    Log::debug('public channel hit');
    return true;
});

Broadcast::channel('user.{id}', function($user, $id) {
    Log::debug('private channel hit');
    return (int) $user->user_id === (int) $id;

    // return true;
});

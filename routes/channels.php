<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('public', function() {
    return true;
});

Broadcast::channel('private.{id}', function($user, $id) {
    return (int) $user->user_id === (int) $id;
});

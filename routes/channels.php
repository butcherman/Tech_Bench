<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/**
 * User Notifications
 */
Broadcast::channel('user-notification.{username}', function(User $user,string $username) {
    return $user->username ===  $username;
});






Broadcast::channel('user.{id}', function($user, $id) {
    return (int) $user->user_id === (int) $id;
});

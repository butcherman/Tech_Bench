<?php

use App\Models\AppSettings;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/**
 * User Notifications
 */
Broadcast::channel('user-notification.{username}', function (User $user, string $username) {
    return $user->username === $username;
});

Broadcast::channel('user.{id}', function ($user, $id) {
    return (int) $user->user_id === (int) $id;
});

/**
 * Live Stream Channel for running administrative processes
 */
Broadcast::channel('process.{type}', function (User $user) {
    return $user->can('viewAny', AppSettings::class);
});

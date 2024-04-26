<?php

use App\Models\User;
use Illuminate\Support\Facades\Broadcast;

/*
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

/**
 * Customer Monitoring Channel
 */
Broadcast::channel('customer.{slug}', function (User $user) {
    Log::debug('Channel customer.{slug} being subscribed to by ' . $user->username);

    return $user ? true : false;
});

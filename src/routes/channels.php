<?php

use App\Features\FileLinkFeature;
use App\Models\User;
use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Log;

/*
|-------------------------------------------------------------------------------
| User Notification Channel
|-------------------------------------------------------------------------------
*/

Broadcast::channel('App.Models.User.{id}', function (User $user, int $id) {
    Log::debug(
        'User '.$user->username.' connecting to Notification Broadcast Channel'
    );

    return (int) $user->user_id === (int) $id;
});

/*
|------------------------------------------------------------------------------
| Administrative Channel for Broadcasting Admin Level Events
|------------------------------------------------------------------------------
*/

Broadcast::channel('administration-channel', function (User $user) {
    Log::debug(
        'User '.$user->username.' connecting to Administration Broadcast Channel'
    );

    return Gate::allows('admin-link', $user);
});

/*
|------------------------------------------------------------------------------
| Customer Channels
|------------------------------------------------------------------------------
*/

// Broadcast::channel('customer.{slug}', function (User $user, string $slug) {
//     Log::debug(
//         'User ' . $user->username . ' registering to Customer Channel - ' . $slug
//     );

//     return $user ? true : false;
// });

// Broadcast::channel(
//     'customer-site.{siteSlug}',
//     function (User $user, string $siteSlug) {
//         Log::debug(
//             'User ' . $user->username . ' registering to Customer Site Channel - ' .
//                 $siteSlug
//         );

//         return $user ? true : false;
//     }
// );

// Broadcast::channel(
//     'customer-equipment.{custEquipId}',
//     function (User $user, int $custEquipId) {
//         Log::debug(
//             'User ' . $user->username . ' registering to Customer Equipment Channel - ' .
//                 $custEquipId
//         );

//         return $user ? true : false;
//     }
// );

/*
|------------------------------------------------------------------------------
| Active Tech Tip Channel
|------------------------------------------------------------------------------
*/

// Broadcast::channel('tech-tips.{tip_id}', function (User $user, int $tip_id) {
//     Log::debug(
//         'User ' . $user->username . ' registering to Tech Tip Channel for Tip ' .
//             $tip_id
//     );

//     return $user ? true : false;
// });

/*
|------------------------------------------------------------------------------
| Active File Link Channel
|------------------------------------------------------------------------------
*/

// Broadcast::channel('file-link.{link_id}', function (User $user, int $link_id) {
//     Log::debug(
//         'User ' . $user->username . ' registering to File Link Channel for link ID ' . $link_id
//     );

//     if (! config('file-link.feature_enabled')) {
//         return false;
//     }

//     return $user->features()->active(FileLinkFeature::class);
// });

<?php

namespace App\Domains\User;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;


class UserNotifications
{
    public function markNotificationRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        Log::debug('Marked user notification as read for '.Auth::user()->full_name.'. Data - ', array($notification));
        $notification->markAsRead();

        return true;
    }

    public function deleteNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        Log::debug('Deleted user notification for '.Auth::user()->full_name.'. Data - ', array($notification));
        $notification->delete();

        return true;
    }
}

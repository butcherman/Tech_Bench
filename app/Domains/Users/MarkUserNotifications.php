<?php

namespace App\Domains\Users;

use Illuminate\Support\Facades\Auth;

class MarkUserNotifications
{
    public function markNotificationRead($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        $notification->markAsRead();

        return true;
    }

    public function deleteNotification($id)
    {
        $notification = Auth::user()->notifications()->where('id', $id)->where('notifiable_id', Auth::user()->user_id)->first();
        $notification->delete();

        return true;
    }

}

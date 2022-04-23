<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read
     */
    public function edit($id)
    {
        Auth::user()->notifications()->where('id', $id)->first()->markAsRead();
        Log::debug('Marked message ID '.$id.' as read');

        return [
            'list'   => Auth::user()->notifications,
            'unread' => Auth::user()->unreadNotifications->count(),
        ];
    }

    /**
     * Remove a user notification
     */
    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->first()->delete();
        Log::debug('Deleted message ID '.$id);

        return [
            'list'   => Auth::user()->notifications,
            'unread' => Auth::user()->unreadNotifications->count(),
        ];
    }
}

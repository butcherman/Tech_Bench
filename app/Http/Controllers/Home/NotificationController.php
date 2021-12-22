<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read
     */
    public function edit($id)
    {
        Auth::user()->notifications()->where('id', $id)->first()->markAsRead();

        return Auth::user()->notifications;
    }

    /**
     * Remove a user notification
     */
    public function destroy($id)
    {
        Auth::user()->notifications()->where('id', $id)->first()->delete();

        return Auth::user()->notifications;
    }
}

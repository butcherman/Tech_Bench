<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\NotificationRequest;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Mark a notification as read, or delete a notification
     */
    public function __invoke(NotificationRequest $request)
    {
        $request->handleNotifications();

        return back();
    }
}
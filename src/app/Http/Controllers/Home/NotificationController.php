<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\NotificationRequest;
use Inertia\Inertia;

class NotificationController extends Controller
{
    /**
     * Show the notifications page
     */
    public function index()
    {
        return Inertia::render('Home/Notifications');
    }

    /**
     * Mark a notification as read, or delete a notification
     */
    public function update(NotificationRequest $request)
    {
        $request->handleNotifications();

        return back();
    }
}

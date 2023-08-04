<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\NotificationRequest;

/**
 * Save, Mark as Read, Delete or Fetch user notifications
 */
class NotificationController extends Controller
{
    public function __invoke(NotificationRequest $request)
    {
        $request->processNotifications();

        return response()->json([
            'list' => $request->user()->notifications,
            'new' => $request->user()->unreadNotifications->count(),
        ]);
    }
}

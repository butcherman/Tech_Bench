<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\NotificationRequest;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(NotificationRequest $request)
    {
        $request->processNotifications();

        return response()->json([
            'list' => $request->user()->notifications,
            'new' => $request->user()->unreadNotifications->count(),
        ]);
    }
}

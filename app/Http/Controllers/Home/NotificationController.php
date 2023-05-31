<?php

namespace App\Http\Controllers\Home;

use App\Http\Controllers\Controller;
use App\Http\Requests\Home\NotificationRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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

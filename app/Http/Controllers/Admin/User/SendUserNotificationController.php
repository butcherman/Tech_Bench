<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserNotificationRequest;
use App\Models\User;
use App\Notifications\User\SendUserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

class SendUserNotificationController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserNotificationRequest $request, User $user)
    {
        Notification::send($user, new SendUserNotification($request));
        Log::info('Administrator '.$request->user()->username.' has sent a message to '.$user->username);

        return back()->with('success', __('admin.user.notification_sent'));
    }
}

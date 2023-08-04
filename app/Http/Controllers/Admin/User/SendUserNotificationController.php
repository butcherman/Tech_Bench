<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserNotificationRequest;
use App\Models\User;
use App\Notifications\User\SendUserNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;

/**
 * Send a manually created notification/message to a user
 */
class SendUserNotificationController extends Controller
{
    public function __invoke(UserNotificationRequest $request, User $user)
    {
        $this->authorize('manage', User::class);

        Notification::send($user, new SendUserNotification($request));
        Log::info('Administrator '.$request->user()->username.' has sent a message to '.$user->username);

        return back()->with('success', __('admin.user.notification_sent'));
    }
}

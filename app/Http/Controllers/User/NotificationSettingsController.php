<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserNotificationRequest;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NotificationSettingsController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(UserNotificationRequest $request, User $user)
    {
        $request->updateSettings();

        Log::stack(['daily', 'user'])->info('User Notification Settings ahve been updated for '.$user->username, $request->toArray());

        return back()->with('success', __('user.notification'));
    }
}

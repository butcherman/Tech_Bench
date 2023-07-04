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
        $reVerify = $request->doWeReVerify();
        $request->updateSettings();
        $user->update($request->only(['phone', 'receive_sms']));

        // If the user has not verified their mobile number, they will have to do so
        if ($reVerify) {
            app('redirect')->setIntendedUrl(route('user.settings.index'));
            $user->generateVerificationCode(true);
            $request->session()->put(['verify_sms' => true]);

            return redirect(route('2fa.index'))->with('success', __('user.verify-sms'));
        }

        Log::stack(['daily', 'user'])->info('User Notification Settings have been updated for '.$user->username, $request->toArray());

        return back()->with('success', __('user.notification'));
    }
}

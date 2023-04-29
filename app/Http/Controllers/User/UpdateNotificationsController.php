<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\User\UserNotificationsRequest;
use App\Models\UserSetting;
use Illuminate\Support\Facades\Log;

class UpdateNotificationsController extends Controller
{
    /**
     * Update the users notification settings
     */
    public function __invoke(UserNotificationsRequest $request)
    {
        foreach ($request->settingsData as $key => $value) {
            UserSetting::where('user_id', $request->user_id)
                ->where('setting_type_id', str_replace('type_id_', '', $key))
                ->update([
                    'value' => $value,
                ]);
        }

        Log::stack(['auth', 'user'])
            ->info('User Notification Settings for User ID - '.$request->user_id.' has been update by '
                    .$request->user()->username);

        return back()->with('success', __('user.notification_updated'));
    }
}

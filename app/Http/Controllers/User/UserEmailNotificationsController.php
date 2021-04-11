<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\UserEmailNotifications;
use App\Http\Requests\User\UserEmailNotificationsRequest;

class UserEmailNotificationsController extends Controller
{
    /**
     *  Update the users email notification settings
     */
    public function update(UserEmailNotificationsRequest $request, $id)
    {
        $user = UserEmailNotifications::findOrFail($id);
        $user->update($request->toArray());

        return back()->with(['message' => 'Email Notifications Updated', 'type' => 'success']);
    }
}

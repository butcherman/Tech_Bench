<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\DeviceToken;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RemoveDeviceController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, User $user, DeviceToken $device)
    {
        $this->authorize('update', $user);

        $device->delete();

        Log::stack(['auth', 'daily'])->info('User '.$request->user()->username.
            ' has removed Device Token for '.$user->username, $device->toArray());

        return back()->with('success', __('user.device-removed'));
    }
}

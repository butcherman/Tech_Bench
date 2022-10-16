<?php

namespace App\Http\Controllers\User;

use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Traits\UserSettingsTrait;
use App\Http\Controllers\Controller;

class UserSettingsController extends Controller
{
    use UserSettingsTrait;

    public function __invoke(Request $request)
    {
        return Inertia::render('User/Settings', [
            'user'     => $request->user()->makeVisible('user_id'),
            'settings' => $this->filterUserSettings($request->user()),
        ]);
    }
}

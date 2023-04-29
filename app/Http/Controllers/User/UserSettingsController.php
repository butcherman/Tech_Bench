<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Traits\UserSettingsTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserSettingsController extends Controller
{
    use UserSettingsTrait;

    public function __invoke(Request $request)
    {
        return Inertia::render('User/Settings', [
            'user' => $request->user()->makeVisible('user_id'),
            'settings' => $this->filterUserSettings($request->user()),
        ]);
    }
}

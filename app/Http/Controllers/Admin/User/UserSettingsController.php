<?php

namespace App\Http\Controllers\Admin\User;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\User\UserSettingsRequest;
use App\Models\User;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class UserSettingsController extends Controller
{
    use AppSettingsTrait;

    public function get()
    {
        $this->authorize('manage', User::class);

        return Inertia::render('Admin/User/Settings', [
            'allow_login' => (bool) config('services.azure.allow_login'),
            'allow_register' => (bool) config('services.azure.allow_register'),
            'tenant' => config('services.azure.tenant'),
            'client_id' => config('services.azure.client_id'),
            'client_secret' => config('services.azure.client_secret') ? __('admin.fake-password') : '',
            'redirectUri' => config('app.url').'/auth/callback',
        ]);
    }

    public function set(UserSettingsRequest $request)
    {
        $this->saveSettingsArray($request->except(['redirectUri']), 'services.azure');

        Log::notice('User Settings updated by '.$request->user()->username, $request->except('client_secret'));

        return back()->with('success', __('admin.user.settings_updated'));
    }
}

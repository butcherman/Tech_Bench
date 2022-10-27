<?php

namespace App\Http\Controllers\Admin\Logs;

use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Traits\AppSettingsTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogSettingsRequest;

class SetLogSettingsController extends Controller
{
    use AppSettingsTrait;

    /**
     * Submit the log settings
     */
    public function __invoke(LogSettingsRequest $request)
    {
        foreach($request->all() as $key => $setting)
        {
            $this->saveSettings($request->getConfigkey($key), $setting);
        }

        Log::notice('Log level settings updated by '.Auth::user()->username);

        return back()->with('success', __('admin.log_settings_updated'));
    }
}

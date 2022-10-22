<?php

namespace App\Http\Controllers\Admin\Config;

use Illuminate\Support\Facades\Log;
use App\Traits\AppSettingsTrait;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;

class SetConfigController extends Controller
{
    use AppSettingsTrait;

    /**
     * Save the Application Configuration Settings
     */
    public function __invoke(SettingsRequest $request)
    {
        foreach($request->all() as $key => $value)
        {
            $this->saveSettings($request->getConfigKey($key), $value);
        }

        Log::notice('App Settings have been updated by '.$request->user()->username);
        return back()->with('success', __('admin.config_updated'));
    }
}

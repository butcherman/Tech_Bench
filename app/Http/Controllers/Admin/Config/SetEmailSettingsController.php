<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\EmailSettingsRequest;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;

class SetEmailSettingsController extends Controller
{
    use AppSettingsTrait;

    /**
     * Save the email settings
     */
    public function __invoke(EmailSettingsRequest $request)
    {
        foreach ($request->all() as $key => $value) {
            $this->saveSettings($request->getConfigKey($key), $value);
        }

        Log::notice('App Email Settings have been updated by '.$request->user()->username);

        return back()->with('success', __('admin.email_updated'));
    }
}

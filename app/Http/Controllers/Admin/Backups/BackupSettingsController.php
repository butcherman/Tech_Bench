<?php

namespace App\Http\Controllers\Admin\Backups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BackupSettingsRequest;
use App\Traits\AppSettingsTrait;

class BackupSettingsController extends Controller
{
    use AppSettingsTrait;

    /**
     * Save the backup settings
     */
    public function __invoke(BackupSettingsRequest $request)
    {
        // $this->saveRequest($request);
        foreach($request->all() as $key => $value)
        {
            if($value !== null)
            {
                $this->saveSettings($request->getConfigkey($key), $value);
            }
            else
            {
                $this->clearSetting($request->getConfigkey($key));
            }
        }

        return back()->with('success', __('admin.backup_settings_update'));
    }
}

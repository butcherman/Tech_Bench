<?php

namespace App\Traits;

use App\Models\AppSettings;

/**
 *  App Settings trait will set and clear configuration settings
 */
trait AppSettingsTrait
{
    //  Save an individual setting into the database so that it can be modified from the hard coded setting
    protected function saveSettings($key, $value)
    {
        if ($value !== __('admin.fake_password') && $value !== null) {
            AppSettings::firstOrCreate(
                ['key' => $key],
                ['value' => $value]
            )->update(['value' => $value]);
        }
    }

    //  Clear a setting from the database
    protected function clearSetting($key)
    {
        $data = AppSettings::where('key', $key)->first();
        if ($data) {
            $data->delete();
        }
    }

    //  Array must be in the form of ['key' => 'value] in order to be properly updated
    protected function saveSettingsArray($settingArray, $prefix = '')
    {
        foreach ($settingArray as $key => $value) {
            $newKey = $prefix !== '' ? $prefix.'.'.$key : $key;

            $this->saveSettings($newKey, $value);
        }
    }
}

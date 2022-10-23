<?php

namespace App\Traits;

use App\Models\AppSettings;

/**
 *  AllowTrait only has one function to check permission for the policies
 */
trait AppSettingsTrait
{
    //  Save an individual setting into the database so that it can be modified from the hard coded setting
    protected function saveSettings($key, $value)
    {
        if($value !== __('admin.fake_password'))
        {
            AppSettings::firstOrCreate(
                ['key'   => $key],
                ['value' => $value]
            )->update(['value' => $value]);
        }
    }

    //  Array must be in the form of ['key' => 'value] in order to be properly updated
    protected function saveArray($settingArray)
    {
        foreach($settingArray as $key => $value)
        {
            $this->saveSettings($key, $value);
        }
    }
}

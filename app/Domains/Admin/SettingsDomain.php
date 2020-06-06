<?php

namespace App\Domains\Admin;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Log;

use App\Settings;

use App\Domains\FilesDomain;

class SettingsDomain
{
    //  Update the Settings table in the database for a new system setting.
    public function updateSettings($key, $value)
    {
        Settings::firstOrCreate(
            ['key' => $key],
            ['key' => $key, 'value' => $value]
        )->update(['value' => $value]);

        return true;
    }

    //  Save a new application logo
    public function saveNewLogo(UploadedFile $file)
    {
        $logoName = (new FilesDomain('images/logo', 'public'))->saveFile($file);
        $logoPath = config('filesystems.disks.public.url').'/images/logo/';
        $this->updateSettings('app.logo', $logoPath.$logoName);

        return $logoPath.$logoName;
    }
}

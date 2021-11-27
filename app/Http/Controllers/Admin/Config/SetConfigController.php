<?php

namespace App\Http\Controllers\Admin\Config;

use App\Events\Admin\GlobalConfigUpdatedEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\SettingsRequest;
use App\Traits\AppSettingsTrait;

class SetConfigController extends Controller
{
    use AppSettingsTrait;

    /**
     * Save the submitted configuration settings
     */
    public function __invoke(SettingsRequest $request)
    {
        $this->saveSettings('app.timezone',             $request->timezone);
        $this->saveSettings('filesystems.max_filesize', $request->filesize);
        $this->saveSettings('app.url',                  $request->url);

        event(new GlobalConfigUpdatedEvent($request));
        return back()->with([
            'message' => 'Configuration Updated',
            'type'    => 'success',
        ]);
    }
}

<?php

namespace App\Http\Controllers\Admin\Logs;

use App\Traits\AppSettingsTrait;

use App\Http\Controllers\Controller;
use App\Events\Admin\LogSettingsUpdatedEvent;
use App\Http\Requests\Admin\LogSettingsRequest;

class SetLogSettingsController extends Controller
{
    use AppSettingsTrait;

    /**
     * Update the Log Settings
     */
    public function __invoke(LogSettingsRequest $request)
    {
        $this->saveSettings('logging.days', $request->days);
        $this->saveSettings('logging.log_level', $request->level);

        event(new LogSettingsUpdatedEvent(['days' => $request->days, 'level' => $request->level]));
        return back()->with([
            'message' => 'Log Settings Updated',
            'type'    => 'success',
        ]);
    }
}

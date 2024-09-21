<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\LogSettingsRequest;
use App\Models\AppSettings;
use App\Service\Maint\LogUtilitiesService;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LogSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('viewAny', AppSettings::class);
        $logObj = new LogUtilitiesService;

        return Inertia::render('Maint/LogSettings', [
            'days' => (int) config('logging.channels.daily.days'),
            'log-level' => config('logging.channels.daily.level'),
            'level-list' => $logObj->getLogLevels(),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(LogSettingsRequest $request)
    {
        $request->saveLogSettings();

        Log::info('Log settings updated by '.$request->user()->username);

        return back()->with('success', __('admin.logging.updated'));
    }
}

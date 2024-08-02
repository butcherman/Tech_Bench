<?php

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\LogSettingsRequest;
use App\Models\AppSettings;
use App\Traits\LogUtilitiesTrait;
use Inertia\Inertia;

class LogSettingsController extends Controller
{
    use LogUtilitiesTrait;

    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/LogSettings', [
            'days' => (int) config('logging.days'),
            'log-level' => config('logging.log_level'),
            'level-list' => $this->logLevels,
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(LogSettingsRequest $request)
    {
        $request->saveLogSettings($this->logChannels);

        return back()->with('success', __('admin.logging.updated'));
    }
}

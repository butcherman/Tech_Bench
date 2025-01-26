<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Enums\LogLevels;
use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\LogSettingsRequest;
use App\Models\AppSettings;
use App\Services\Maint\LogUtilitiesService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LogSettingsController extends Controller
{
    public function __construct(protected LogUtilitiesService $svc) {}

    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/LogSettings', [
            'days' => (int) config('logging.channels.daily.days'),
            'log-level' => config('logging.channels.daily.level'),
            'level-list' => $this->svc->getLogLevels(),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(LogSettingsRequest $request)
    {
        $this->svc->updateLogSettings($request->safe()->collect());

        Log::info('Log settings updated by ' . $request->user()->username);

        return back()->with('success', __('admin.logging.updated'));
    }
}

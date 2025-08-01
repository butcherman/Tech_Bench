<?php

namespace App\Http\Controllers\Maintenance\Logs;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\LogSettingsRequest;
use App\Models\AppSettings;
use App\Services\Maintenance\LogUtilitiesService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class LogSettingsController extends Controller
{
    public function __construct(protected LogUtilitiesService $svc) {}

    /**
     * Display the log settings form.
     */
    public function show(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/LogSettings', [
            'days' => (int) config('logging.channels.daily.days'),
            'log-level' => config('logging.channels.daily.level'),
            'level-list' => $this->svc->getLogLevels(),
        ]);
    }

    /**
     * Update the log settings
     */
    public function update(LogSettingsRequest $request): RedirectResponse
    {
        $this->svc->updateLogSettings($request->safe()->collect());

        Log::info('Log settings updated by '.$request->user()->username);

        return back()->with('success', __('admin.logging.updated'));
    }
}

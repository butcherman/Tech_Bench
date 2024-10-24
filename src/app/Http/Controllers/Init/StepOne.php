<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Service\Admin\ApplicationSettingsService;
use App\Service\TimezoneList;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StepOne extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $settingsData = $request->session()
            ->get('setup.basic-settings') ?: $this->svc->getBasicSettings();

        return Inertia::render('Init/StepOne', [
            'step' => 1,
            'settings' => $settingsData,
            'timezone-list' => TimezoneList::Build(),
        ]);
    }
}

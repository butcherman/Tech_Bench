<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepTwoController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Step 2.  Email Settings
     */
    public function __invoke(Request $request)
    {
        $settingsData = $request->session()
            ->get('setup.email-settings') ?: $this->svc->getEmailSettings();

        return Inertia::render('Init/StepTwo', [
            'step' => 2,
            'settings' => $settingsData,
        ]);
    }
}

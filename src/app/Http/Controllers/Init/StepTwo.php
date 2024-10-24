<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Service\Admin\ApplicationSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StepTwo extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $settingsData = $request->session()
            ->get('setup.email-settings') ?: $this->svc->getEmailSettings();

        return Inertia::render('Init/StepTwo', [
            'step' => 2,
            'settings' => $settingsData,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Service\Admin\ApplicationSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StepThree extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request): Response
    {
        $settingsData = $request->session()
            ->get('setup.user-settings') ?: $this->svc->getPasswordSettings();

        return Inertia::render('Init/StepThree', [
            'step' => 3,
            'policy' => $settingsData,
        ]);
    }
}

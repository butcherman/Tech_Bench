<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserGlobalSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepThreeController extends Controller
{
    public function __construct(protected UserGlobalSettingsService $svc) {}

    /**
     * Step 3.  User Settings.
     */
    public function __invoke(Request $request)
    {
        $settingsData = $request->session()
            ->get('setup.user-settings') ?: $this->svc->getPasswordPolicy();

        return Inertia::render('Init/StepThree', [
            'step' => 3,
            'policy' => $settingsData,
        ]);
    }
}

<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Services\Admin\UserGlobalSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class StepThreeController extends Controller
{
    public function __construct(protected UserGlobalSettingsService $svc) {}

    /**
     * Step 3.  User Settings.
     */
    public function __invoke(Request $request): Response
    {
        $pass = $this->svc->getPasswordPolicy();
        $mfa = $this->svc->getTwoFaConfig();

        $settingsData = $request->session()
            ->get('setup.security', [
                'password' => $pass,
                'twoFa' => $mfa,
            ]);

        return Inertia::render('Init/StepThree', [
            'step' => 3,
            'policy' => $settingsData,
        ]);
    }
}

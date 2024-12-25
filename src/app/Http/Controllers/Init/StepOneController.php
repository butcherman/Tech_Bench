<?php

namespace App\Http\Controllers\Init;

use App\Facades\TimezoneList;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepOneController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Step 1.  Basic Application Settings
     */
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        $settingsData = $request->session()
            ->get('setup.basic-settings') ?: $this->svc->getBasicSettings();

        return Inertia::render('Init/StepOne', [
            'step' => 1,
            'settings' => fn() => $settingsData,
            'timezone-list' => fn() => TimezoneList::build(),
        ]);
    }
}

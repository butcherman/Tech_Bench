<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * First time setup - Step 5 - Wrapping it all up
 */
class StepFive extends Controller
{
    use AppSettingsTrait;

    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        $this->clearSetting('app.first_time_setup');

        return Inertia::render('Init/StepFive', [
            'stepId' => 5,
        ]);
    }
}

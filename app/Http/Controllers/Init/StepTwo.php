<?php

namespace App\Http\Controllers\Init;

use App\Actions\BuildTimezoneList;
use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Illuminate\Http\Request;
use Inertia\Inertia;

/**
 * First time setup - Step 2 - Basic App Settings
 */
class StepTwo extends Controller
{
    public function __invoke(Request $request)
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Init/StepTwo', [
            'stepId' => 2,
            'settings' => [
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'max_filesize' => config('filesystems.max_filesize'),
            ],
            'tz-list' => (new BuildTimezoneList)->build(),
        ]);
    }
}

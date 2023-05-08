<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Models\AppSettings;
use Inertia\Inertia;

class StepTwo extends Controller
{
    /**
     * Basic App settings for first time setup
     */
    public function __invoke()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Init/StepTwo', [
            'settings' => [
                'url' => config('app.url'),
                'timezone' => config('app.timezone'),
                'filesize' => config('filesystems.max_filesize'),
                'redirectUri' => config('app.url').'/auth/callback',
            ],
            'tz_list' => \Timezonelist::toArray(),
        ]);
    }
}

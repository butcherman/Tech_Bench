<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Service\TimezoneList;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepTwo extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        return Inertia::render('Init/StepTwo', [
            'step' => 2,
            'settings' => [
                'url' => preg_replace('(^https?://)', '', config('app.url')),
                'timezone' => config('app.timezone'),
                'max_filesize' => config('filesystems.max_filesize'),
            ],
            'timezone-list' => TimezoneList::Build(),
        ]);
    }
}

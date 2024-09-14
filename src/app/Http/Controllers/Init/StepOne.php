<?php

namespace App\Http\Controllers\Init;

use App\Http\Controllers\Controller;
use App\Service\TimezoneList;
use App\Models\UserRole;
use App\Service\Cache;
use Illuminate\Http\Request;
use Inertia\Inertia;

class StepOne extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request)
    {
        $settingsData = [
            'url' => preg_replace('(^https?://)', '', config('app.url')),
            'timezone' => config('app.timezone'),
            'max_filesize' => (int) config('filesystems.max_filesize'),
            'company_name' => config('app.company_name'),
        ];

        if ($request->session()->has('setup.basic-settings')) {
            $settingsData = $request->session()->get('setup.basic-settings');
        }

        return Inertia::render('Init/StepOne', [
            'step' => 1,
            'settings' => $settingsData,
            'timezone-list' => TimezoneList::Build(),
        ]);
    }
}

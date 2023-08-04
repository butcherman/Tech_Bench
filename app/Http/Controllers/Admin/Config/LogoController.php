<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogoRequest;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

/**
 * View and adjust Application Logo (company logo)
 */
class LogoController extends Controller
{
    use AppSettingsTrait;

    public function get()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Logo', [
            'logo' => config('app.logo'),
        ]);
    }

    public function set(LogoRequest $request)
    {
        $location = $request->processLogo($request->logo);
        $this->saveSettings('app.logo', $location);

        Log::notice('New App Logo has been uploaded by '.$request->user()->username, [
            'file' => $location,
        ]);

        return back();
    }
}

<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogoRequest;
use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class LogoController extends Controller
{
    use AppSettingsTrait;

    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Logo');
    }

    /**
     * Update the resource in storage.
     */
    public function update(LogoRequest $request)
    {
        $logoLocation = $request->processLogo();
        $this->saveSettings('app.logo', $logoLocation);

        Log::notice('New Tech Bench Logo uploaded by '.$request->user()->username, [
            'file-location' => $logoLocation,
        ]);

        return response()->noContent();
    }
}

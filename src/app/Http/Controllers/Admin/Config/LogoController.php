<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogoRequest;
use App\Models\AppSettings;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class LogoController extends Controller
{
    use AppSettingsTrait;

    /**
     * Display the current logo.
     */
    public function show(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Logo');
    }

    /**
     * Update the current logo
     */
    public function update(LogoRequest $request): HttpResponse
    {
        $logoLocation = $request->processLogo();
        $this->saveSettings('app.logo', $logoLocation);

        Log::notice('New Tech Bench Logo uploaded by '.$request->user()->username, [
            'file-location' => $logoLocation,
        ]);

        return response()->noContent();
    }
}

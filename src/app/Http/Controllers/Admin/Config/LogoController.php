<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\LogoRequest;
use App\Models\AppSettings;
use App\Service\Admin\ApplicationSettingsService;
use App\Traits\AppSettingsTrait;
use Illuminate\Http\Response as HttpResponse;
use Inertia\Inertia;
use Inertia\Response;

class LogoController extends Controller
{
    use AppSettingsTrait;

    public function __construct(protected ApplicationSettingsService $svc) {}

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
        $this->svc->processLogo($request);

        return response()->noContent();
    }
}

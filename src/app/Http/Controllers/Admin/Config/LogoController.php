<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\LogoRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class LogoController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Show the form for editing the resource.
     */
    public function edit(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Logo');
    }

    /**
     * Update the resource in storage.
     */
    public function update(LogoRequest $request): HttpResponse
    {
        $storedFile = $this->svc->updateLogo($request->safe()->collect());

        Log::notice(
            'New Tech Bench Logo uploaded by '.$request->user()->username, [
                'file-location' => $storedFile,
            ]
        );

        return response()->noContent();
    }
}

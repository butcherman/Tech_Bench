<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\LogoRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class LogoController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Show the form for uploading a new logo.
     */
    public function edit(): Response
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Config/Logo', [
            'current-logo' => config('app.logo'),
            'is-default' => config('app.logo') === '/images/TechBenchLogo.png',
        ]);
    }

    /**
     * Upload and save a new logo
     */
    public function update(LogoRequest $request): RedirectResponse
    {
        $location = $this->svc->updateLogo($request->input('upload_id'));

        Log::notice(
            'New Tech Bench Logo uploaded by '.$request->user()->username,
            [
                'file-location' => $location,
            ]
        );

        return back()->with(['success' => 'Logo Saved']);
    }

    /**
     * Delete the current logo and revert to the default one
     */
    public function destroy(): RedirectResponse
    {
        $this->authorize('viewAny', AppSettings::class);

        $this->svc->destroyLogo();

        return back()->with('success', 'Logo Deleted');
    }
}

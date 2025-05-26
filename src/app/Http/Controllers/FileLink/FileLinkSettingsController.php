<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\FileLinkSettingsRequest;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkAdministrationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkSettingsController extends Controller
{
    public function __construct(protected FileLinkAdministrationService $svc) {}

    /**
     * Show form to edit File Link Settings.
     */
    public function edit(): Response
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render(
            'FileLink/Admin/Settings',
            $this->svc->getFileLinkSettings()
        );
    }

    /**
     * Update the File Link Settings
     */
    public function update(FileLinkSettingsRequest $request): RedirectResponse
    {
        $this->svc->saveFileLinkSettings($request->safe()->collect());

        return back()->with('success', 'Settings Updated');
    }
}

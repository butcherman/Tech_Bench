<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileLinkSettingsRequest;
use App\Models\FileLink;
use App\Services\FileLink\FileLinkAdministrationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkSettingsController extends Controller
{
    public function __construct(protected FileLinkAdministrationService $svc) {}

    /**
     * Show the form for editing the File Link Settings.
     */
    public function edit(): Response
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinks/Settings', [
            'settings' => $this->svc->getFileLinkSettings(),
        ]);
    }

    /**
     * Update the File Link Settings.
     */
    public function update(FileLinkSettingsRequest $request): RedirectResponse
    {
        $this->svc->saveFileLinkSettings($request->safe()->collect());

        Log::info(
            'File Link Settings updated by '.request()->user()->username,
            $request->toArray()
        );

        return back()->with('success', 'File Link Settings Updated');
    }
}

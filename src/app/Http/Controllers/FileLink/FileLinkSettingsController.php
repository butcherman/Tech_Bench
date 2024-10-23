<?php

namespace App\Http\Controllers\FileLink;

use App\Http\Controllers\Controller;
use App\Http\Requests\FileLink\FileLinkSettingsRequest;
use App\Models\FileLink;
use App\Service\FileLink\FileLinkAdministrationService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class FileLinkSettingsController extends Controller
{
    public function __construct(protected FileLinkAdministrationService $svc) {}

    /**
     * Display the resource.
     */
    public function show(): Response
    {
        $this->authorize('manage', FileLink::class);

        return Inertia::render('FileLinks/Settings', [
            'settings' => [
                'default_link_life' => config('file-link.default_link_life'),
                'auto_delete' => (bool) config('file-link.auto_delete'),
                'auto_delete_days' => config('file-link.auto_delete_days'),
                'auto_delete_override' => (bool) config('file-link.auto_delete_override'),
            ],
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(FileLinkSettingsRequest $request): RedirectResponse
    {
        $this->svc->saveFileLinkSettings($request->collect());

        return back()->with('success', 'File Link Settings Updated');
    }
}

<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\LogoRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use App\Services\File\TusUploadService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LogoController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc, protected TusUploadService $uploadSvc) {}

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
        $logoFile = $this->uploadSvc->getCompletedUpload($request->input('upload_id'));

        if (! $this->uploadSvc->validateMimeType($logoFile, [
            'image/jpg', 'image/jpeg', 'image/bmp', 'image/png', 'image/gif',
        ])) {
            $this->uploadSvc->deleteUpload($logoFile);

            throw ValidationException::withMessages([
                'upload_id' => 'The uploaded file is not a supported image type.',
            ]);
        }

        $location = $this->svc->updateLogo($logoFile);
        $this->uploadSvc->finalizeUpload($logoFile, $location);

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

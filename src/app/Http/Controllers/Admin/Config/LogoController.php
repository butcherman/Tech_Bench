<?php

namespace App\Http\Controllers\Admin\Config;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Config\LogoRequest;
use App\Models\AppSettings;
use App\Services\Admin\ApplicationSettingsService;
use App\Services\Upload\TusUploadService;
use ArthurPatriot\Tus\Facades\Tus;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response as HttpResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class LogoController extends Controller
{
    public function __construct(
        protected ApplicationSettingsService $svc,
        protected TusUploadService $uploads
    ) {}

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
    public function update(LogoRequest $request, TusUploadService $uploads): HttpResponse
    {
        $tusFile = $this->uploads->getCompleted(
            $request->validated('upload_id')
        );

        if (! $this->uploads->validateMimeType(
            Tus::storage()->path($tusFile->path),
            [
                'image/jpeg',
                'image/bmp',
                'image/png',
                'image/gif',
            ],
        )) {
            $this->uploads->delete($tusFile);

            throw ValidationException::withMessages([
                'upload_id' => 'The uploaded file is not a supported image type.',
            ]);
        }

        $storedFile = $this->svc->updateLogo($tusFile);

        $this->uploads->delete($tusFile);

        Log::notice(
            'New Tech Bench Logo uploaded by '.$request->user()->username,
            [
                'file-location' => $storedFile,
            ]
        );

        return response()->noContent();
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

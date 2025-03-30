<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\BackupSettingsRequest;
use App\Services\Admin\ApplicationSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;

class BackupSettingsController extends Controller
{
    public function __construct(protected ApplicationSettingsService $svc) {}

    /**
     * Show the backup settings
     */
    public function show(): Response
    {
        $this->authorize('is-installer');

        return Inertia::render('Maint/BackupSettings', [
            'nightly_backup' => (bool) config('backup.nightly_backup'),
            'nightly_cleanup' => (bool) config('backup.nightly_cleanup'),
            'encryption' => config('backup.backup.encryption') === 'default' ? true : false,
            'password' => config('backup.backup.password') ? __('admin.fake-password') : null,
        ]);
    }

    /**
     * Update the backup settings
     */
    public function update(BackupSettingsRequest $request): RedirectResponse
    {
        $this->authorize('is-installer');

        $this->svc->processBackupSettings($request->safe()->collect());

        Log::info('Backup Settings updated by '.$request->user()->username);

        return back()->with('success', __('admin.backups.settings-successful'));
    }
}

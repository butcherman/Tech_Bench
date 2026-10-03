<?php

namespace App\Http\Controllers\Maintenance\Backup;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\BackupSettingsRequest;
use App\Services\Maintenance\BackupSettingsService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;

class BackupSettingsController extends Controller
{
    public function __construct(protected BackupSettingsService $svc) {}

    /**
     * Update the backup settings
     */
    public function __invoke(BackupSettingsRequest $request): RedirectResponse
    {
        $this->authorize('is-installer');

        $this->svc->saveBackupSettings($request->safe()->collect());

        Log::info('Backup Settings updated by '.$request->user()->username);

        return back()->with('success', __('admin.backups.settings-successful'));
    }
}

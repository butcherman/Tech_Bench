<?php

// TODO - Refactor

namespace App\Http\Controllers\Maintenance;

use App\Http\Controllers\Controller;
use App\Http\Requests\Maintenance\BackupSettingsRequest;
use App\Models\AppSettings;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;

class BackupSettingsController extends Controller
{
    /**
     * Display the resource.
     */
    public function show()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Maint/BackupSettings', [
            'nightly_backup' => (bool) config('backup.nightly_backup'),
            'nightly_cleanup' => (bool) config('backup.nightly_cleanup'),
            'encryption' => config('backup.backup.encryption') === 'default' ? true : false,
            'password' => config('backup.backup.password') ? __('admin.fake-password') : null,
            'mail_to' => config('backup.notifications.mail.to'),
        ]);
    }

    /**
     * Update the resource in storage.
     */
    public function update(BackupSettingsRequest $request)
    {
        $request->processBackupSettings();

        Log::info('Backup Settings updated by '.$request->user()->username);

        return back()->with('success', __('admin.backups.settings-successful'));
    }
}

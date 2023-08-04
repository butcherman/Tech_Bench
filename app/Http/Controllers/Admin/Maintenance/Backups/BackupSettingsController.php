<?php

namespace App\Http\Controllers\Admin\Maintenance\Backups;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\BackupSettingsRequest;
use App\Models\AppSettings;
use Inertia\Inertia;

/**
 * View and modify Automated Backup Settings
 */
class BackupSettingsController extends Controller
{
    public function get()
    {
        $this->authorize('viewAny', AppSettings::class);

        return Inertia::render('Admin/Backups/Settings', [
            'backup-settings' => [
                'nightly_backup' => config('backup.nightly_backup'),
                'nightly_cleanup' => config('backup.nightly_cleanup'),
                'encryption' => config('backup.backup.encryption') === 'default' ? true : false,
                'password' => config('backup.backup.password') ? __('admin.fake-password') : null,
                'mail_to' => config('backup.notifications.mail.to'),
            ],

        ]);
    }

    public function set(BackupSettingsRequest $request)
    {
        $request->processBackupSettings();

        return back()->with('success', __('admin.backups.settings-successful'));
    }
}

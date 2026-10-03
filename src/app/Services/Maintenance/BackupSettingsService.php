<?php

namespace App\Services\Maintenance;

use App\Traits\AppSettingsTrait;
use Illuminate\Support\Collection;

class BackupSettingsService
{
    use AppSettingsTrait;

    /**
     * Get the Backup Settings
     */
    public function getBackupSettings(): array
    {
        return [
            'nightly_backup' => (bool) config('backup.nightly_backup'),
            'nightly_cleanup' => (bool) config('backup.nightly_cleanup'),
            'encryption' => config('backup.backup.encryption') === 'default' ? true : false,
            'password' => config('backup.backup.password') ? __('admin.fake-password') : null,
        ];
    }

    /**
     * Save Backup Settings
     */
    public function saveBackupSettings(Collection $requestData): void
    {
        $this->saveSettingsArray(
            $requestData->only(['nightly_backup', 'nightly_cleanup'])->toArray(),
            'backup'
        );

        $this->saveSettings(
            'backup.backup.password',
            $requestData->get('password')
        );

        $this->saveSettings(
            'backup.backup.encryption',
            $requestData->get('encryption') ? 'default' : false
        );
    }
}

<?php

namespace Tests\Unit\Services\Maintenance;

use App\Services\Maintenance\BackupSettingsService;
use Tests\TestCase;

class BackupSettingsServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getBackupSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_backup_settings(): void
    {
        $shouldBe = [
            'nightly_backup' => (bool) config('backup.nightly_backup'),
            'nightly_cleanup' => (bool) config('backup.nightly_cleanup'),
            'encryption' => config('backup.backup.encryption') === 'default' ? true : false,
            'password' => config('backup.backup.password') ? __('admin.fake-password') : null,
        ];

        $testObj = new BackupSettingsService;
        $res = $testObj->getBackupSettings();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | saveBackupSettings()
    |---------------------------------------------------------------------------
    */
    public function test_save_backup_settings(): void
    {
        $data = [
            'nightly_backup' => ! config('backup.nightly_backup'),
            'nightly_cleanup' => ! config('backup.nightly_cleanup'),
            'encryption' => ! config('backup.backup.encryption'),
            'password' => 'randomValue',
        ];

        $testObj = new BackupSettingsService;
        $testObj->saveBackupSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.nightly_backup',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.nightly_cleanup',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.backup.encryption',
        ]);
        $this->assertDatabaseHas('app_settings', [
            'key' => 'backup.backup.password',
        ]);
    }
}

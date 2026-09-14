<?php

namespace Tests\Unit\Services\Maintenance;

use App\DTO\Maintenance\BackupSummary;
use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Services\Maintenance\BackupService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class BackupServiceUnitTest extends TestCase
{
    /** @var array */
    protected $backupFiles = [];

    /*
    |---------------------------------------------------------------------------
    | all()
    |---------------------------------------------------------------------------
    */
    public function test_all(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->all();

        $this->assertEquals(
            $res->pluck('name')->all(),
            ['backup-1.zip', 'backup-2.zip', 'backup-3.zip']
        );
    }

    /*
    |---------------------------------------------------------------------------
    | recent()
    |---------------------------------------------------------------------------
    */
    public function test_recent(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->recent(5);

        $this->assertEquals(
            $res->pluck('name')->all(),
            [
                'backup-1.zip',
                'backup-2.zip',
                'backup-3.zip',
                'backup-4.zip',
                'backup-5.zip',
            ]
        );
    }

    /*
    |---------------------------------------------------------------------------
    | latest()
    |---------------------------------------------------------------------------
    */
    public function test_latest(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->latest();

        $this->assertInstanceOf(BackupSummary::class, $res);
        $this->assertEquals('backup-1.zip', $res->name);
        $this->assertEquals(6, $res->size);
    }

    /*
    |---------------------------------------------------------------------------
    | count()
    |---------------------------------------------------------------------------
    */
    public function test_count(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->count();

        $this->assertEquals(9, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | totalSize()
    |---------------------------------------------------------------------------
    */
    public function test_total_size(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->totalSize();

        $this->assertEquals(6 * 9, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | exists()
    |---------------------------------------------------------------------------
    */
    public function test_exists(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->exists('backup-2.zip');

        $this->assertTrue($res);
    }

    public function test_exists_false(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->exists('backup-88.zip');

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | delete()
    |---------------------------------------------------------------------------
    */
    public function test_delete(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $testObj->delete('backup-1.zip');

        Storage::assertMissing($backupBasename.'backup-1.zip');
    }

    public function test_delete_missing_file(): void
    {
        Exceptions::fake();
        Storage::fake('backups');

        $this->expectException(BackupFileMissingException::class);

        $testObj = new BackupService;
        $testObj->delete('backup-1.zip');

        Exceptions::assertReported(BackupFileMissingException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | path()
    |---------------------------------------------------------------------------
    */
    public function test_path(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-4.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-5.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-6.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-7.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-8.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-9.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->path('backup-2.zip');

        $shouldBe = Storage::disk('backups')->path($backupBasename.'backup-2.zip');

        $this->assertEquals($shouldBe, $res);
    }

    public function test_path_missing_file(): void
    {
        Exceptions::fake();
        Storage::fake('backups');

        $this->expectException(BackupFileMissingException::class);

        $testObj = new BackupService;
        $testObj->path('backup-1.zip');

        Exceptions::assertReported(BackupFileMissingException::class);
    }

    /*
    |---------------------------------------------------------------------------
    | getNextScheduledBackup()
    |---------------------------------------------------------------------------
    */
    public function test_get_next_scheduled_backup(): void
    {
        config(['backup.nightly_backup' => true]);

        $this->travelTo('midnight');

        $now = Carbon::now();
        $next3am = Carbon::today()->setTime(3, 0, 0);

        if ($now->gte($next3am)) {
            $next3am->addDay();
        }

        $testObj = new BackupService;
        $res = $testObj->getNextScheduledBackup();

        $this->assertEquals($next3am->format('M d, Y h:00 A'), $res);
    }

    public function test_get_next_scheduled_backup_later_in_day(): void
    {
        config(['backup.nightly_backup' => true]);

        $this->travelTo('noon');

        $now = Carbon::now();
        $next3am = Carbon::today()->setTime(3, 0, 0);

        if ($now->gte($next3am)) {
            $next3am->addDay();
        }

        $testObj = new BackupService;
        $res = $testObj->getNextScheduledBackup();

        $this->assertEquals($next3am->format('M d, Y h:00 A'), $res);
    }

    public function test_get_next_scheduled_backup_not_enabled(): void
    {
        config(['backup.nightly_backup' => false]);

        $testObj = new BackupService;
        $res = $testObj->getNextScheduledBackup();

        $this->assertEquals('Never', $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getRetentionPolicy()
    |---------------------------------------------------------------------------
    */
    public function test_get_retention_policy(): void
    {
        $strategy = config('backup.cleanup.default_strategy');
        $shouldBe = [
            'daily' => $strategy['keep_daily_backups_for_days'],
            'weekly' => $strategy['keep_weekly_backups_for_weeks'],
            'monthly' => $strategy['keep_monthly_backups_for_months'],
            'yearly' => $strategy['keep_yearly_backups_for_years'],
        ];

        $testObj = new BackupService;
        $res = $testObj->getRetentionPolicy();

        $this->assertEquals($shouldBe, $res);
    }
}

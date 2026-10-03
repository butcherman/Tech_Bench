<?php

namespace Tests\Unit\Services\Maintenance;

use App\DTO\Maintenance\BackupSummary;
use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Models\BackupRun;
use App\Services\Maintenance\BackupService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Exceptions;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;
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
        $backupList = BackupRun::factory()->count(3)->create();

        $testObj = new BackupService;
        $res = $testObj->all();

        $this->assertCount(3, $res);
        $this->assertEquals(
            $backupList->makeHidden('duration')->toArray(),
            $res->makeHidden('duration')->toArray()
        );
        $this->assertInstanceOf(BackupRun::class, $res[0]);
    }

    /*
    |---------------------------------------------------------------------------
    | allFiles()
    |---------------------------------------------------------------------------
    */
    public function test_all_files(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;

        // Create some backup files
        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-2.zip', '123456');
        Storage::disk('backups')->put($backupBasename.'backup-3.zip', '123456');

        $testObj = new BackupService;
        $res = $testObj->allFiles();

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
        $backupList = BackupRun::factory()->count(10)->create();

        $testObj = new BackupService;
        $res = $testObj->recent(5);

        $this->assertCount(5, $res);
        $this->assertInstanceOf(BackupRun::class, $res->last());
    }

    /*
    |---------------------------------------------------------------------------
    | recentFiles()
    |---------------------------------------------------------------------------
    */
    public function test_recent_files(): void
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
        $res = $testObj->recentFiles(5);

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
        $backupList = BackupRun::factory()->count(10)->create();

        $testObj = new BackupService;
        $res = $testObj->latest();

        $this->assertInstanceOf(BackupRun::class, $res);
        $this->assertEquals(
            $backupList->last()->makeHidden('duration')->toArray(),
            $res->makeHidden('duration')->toArray()
        );
    }

    /*
    |---------------------------------------------------------------------------
    | latestFile()
    |---------------------------------------------------------------------------
    */
    public function test_latest_file(): void
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
        $res = $testObj->latestFile();

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
        BackupRun::factory()->count(9)->create();

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
        BackupRun::factory()->count(9)->create(['size' => 6]);

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
        $run = BackupRun::factory()->create([
            'backup_name' => 'backup-1.zip',
            'size' => 6,
        ]);

        $testObj = new BackupService;
        $testObj->delete($run);

        Storage::assertMissing($backupBasename.'backup-1.zip');
        $this->assertDatabaseMissing('backup_runs', [
            'backup_name' => 'backup-1.zip',
            'size' => 6,
        ]);
    }

    public function test_delete_missing_file(): void
    {
        Storage::fake('backups');

        $run = BackupRun::factory()->create([
            'backup_name' => 'backup-1.zip',
            'size' => 6,
        ]);

        $testObj = new BackupService;
        $testObj->delete($run);

        $this->assertDatabaseMissing('backup_runs', [
            'backup_name' => 'backup-1.zip',
            'size' => 6,
        ]);
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
    | download()
    |---------------------------------------------------------------------------
    */
    public function test_download(): void
    {
        Storage::fake('backups');

        $backupBasename = config('backup.backup.name').DIRECTORY_SEPARATOR;
        $response = $this->mock(StreamedResponse::class);

        Storage::disk('backups')->put($backupBasename.'backup-1.zip', '123456');

        $run = BackupRun::factory()->create([
            'backup_name' => 'backup-1.zip',
            'size' => 6,
        ]);

        Storage::shouldReceive('disk')
            ->once()
            ->with('backups')
            ->andReturnSelf();

        Storage::shouldReceive('exists')
            ->with($backupBasename.'backup-1.zip')
            ->andReturnTrue();

        Storage::shouldReceive('download')
            ->once()
            ->with($backupBasename.'backup-1.zip')
            ->andReturn($response);

        $testObj = new BackupService;
        $res = $testObj->download($run);

        $this->assertEquals($response, $res);
    }

    public function test_download_missing_file(): void
    {
        Exceptions::fake();

        $run = BackupRun::factory()->create([
            'backup_name' => 'backup-1.zip',
            'size' => 6,
        ]);

        $this->expectException(BackupFileMissingException::class);

        $testObj = new BackupService;
        $testObj->download($run);

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

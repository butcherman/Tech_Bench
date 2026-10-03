<?php

namespace Tests\Unit\Services\Maintenance;

use App\Actions\Maintenance\ParseLogFile;
use App\DTO\Maintenance\LogFilter;
use App\DTO\Maintenance\LogSnapshot;
use App\Exceptions\Maintenance\LogFileMissingException;
use App\Services\Maintenance\LogUtilitiesService;
use Illuminate\Support\Facades\Storage;
use Mockery\Mock;
use Tests\TestCase;

class LogUtilitiesServiceUnitTest extends TestCase
{
    /** @var ParseLogFile&Mock */
    private ParseLogFile $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->action = $this->mock(ParseLogFile::class);
    }

    /*
    |---------------------------------------------------------------------------
    | getLogLevels()
    |---------------------------------------------------------------------------
    */
    public function test_get_log_levels(): void
    {
        $shouldBe = [
            'emergency',
            'alert',
            'critical',
            'error',
            'warning',
            'notice',
            'info',
            'debug',
        ];

        $testObj = new LogUtilitiesService($this->action);
        $res = $testObj->getLogLevels();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getLogFilePath()
    |---------------------------------------------------------------------------
    */
    public function test_get_log_file_path(): void
    {
        Storage::fake();

        Storage::shouldReceive('disk')->with('logs')->andReturnSelf();
        Storage::shouldReceive('path')
            ->with('Application/testLog.log')
            ->andReturn(storage_path('Application/testLog.log'));

        $testObj = new LogUtilitiesService($this->action);
        $res = $testObj->getLogFilePath('testLog');

        $this->assertEquals(storage_path('Application/testLog.log'), $res);
    }

    /*
    |---------------------------------------------------------------------------
    | validateLogFile()
    |---------------------------------------------------------------------------
    */
    public function test_validate_log_file(): void
    {
        Storage::fake();

        Storage::shouldReceive('disk')->with('logs')->andReturnSelf();
        Storage::shouldReceive('exists')
            ->with('Application/testLog.log')
            ->andReturn(true);

        $testObj = new LogUtilitiesService($this->action);
        $res = $testObj->validateLogFile('testLog');

        $this->assertTrue($res);
    }

    public function test_validate_log_file_false(): void
    {
        Storage::fake();

        Storage::shouldReceive('disk')->with('logs')->andReturnSelf();
        Storage::shouldReceive('exists')
            ->with('Application/testLog.log')
            ->andReturn(false);

        $testObj = new LogUtilitiesService($this->action);
        $res = $testObj->validateLogFile('testLog');

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | query()
    |---------------------------------------------------------------------------
    */
    public function test_query(): void
    {
        $logFile = 'testLog';
        $snapshot = new LogSnapshot(12345);
        $filter = new LogFilter('searchString');

        $testObj = new LogUtilitiesService($this->action);

        $this->action->shouldReceive('__invoke')->with($logFile, $snapshot, $filter, 1)->andReturn([
            'level' => 'INFO',
            'user' => 1,
            'data' => [
                'body' => 'First entry',
                'context' => [],
            ],
        ], );

        $res = $testObj->query($logFile, $snapshot, $filter, 1);

        $this->assertEquals($res, [
            'level' => 'INFO',
            'user' => 1,
            'data' => [
                'body' => 'First entry',
                'context' => [],
            ],
        ]);
    }

    public function test_query_without_page(): void
    {
        $logFile = 'testLog';
        $snapshot = new LogSnapshot(12345);
        $filter = new LogFilter('searchString');

        $testObj = new LogUtilitiesService($this->action);

        $this->action->shouldReceive('__invoke')->with($logFile, $snapshot, $filter, 1)->andReturn([
            'level' => 'INFO',
            'user' => 1,
            'data' => [
                'body' => 'First entry',
                'context' => [],
            ],
        ], );

        $res = $testObj->query($logFile, $snapshot, $filter);

        $this->assertEquals($res, [
            'level' => 'INFO',
            'user' => 1,
            'data' => [
                'body' => 'First entry',
                'context' => [],
            ],
        ]);
    }

    /*
    |---------------------------------------------------------------------------
    | snapshot()
    |---------------------------------------------------------------------------
    */
    public function test_snapshot(): void
    {
        Storage::fake('logs');
        Storage::shouldReceive('disk')->with('logs')->andReturnSelf();
        Storage::shouldReceive('exists')->andReturn(true);
        Storage::shouldReceive('size')->with('Application/testLog.log')
            ->andReturn(12345);

        $testObj = new LogUtilitiesService($this->action);
        $res = $testObj->snapshot('testLog');

        $this->assertEquals(12345, $res->position);
    }

    public function test_snapshot_invalid_log(): void
    {
        Storage::fake('logs');
        Storage::shouldReceive('disk')->with('logs')->andReturnSelf();
        Storage::shouldReceive('exists')->andReturn(false);
        Storage::shouldNotReceive('size')->with('Application/test.log');

        $this->expectException(LogFileMissingException::class);

        $testObj = new LogUtilitiesService($this->action);
        $testObj->snapshot('testLog');
    }

    /*
    |---------------------------------------------------------------------------
    | getListOfLogFiles()
    |---------------------------------------------------------------------------
    */
    public function test_get_list_of_log_files(): void
    {
        Storage::fake();

        $logList = [
            'log1.log',
            'log2.log',
            'log3.log',
            '.gitignore',
            'miscFile.txt',
        ];

        Storage::shouldReceive('disk')->with('logs')->andReturnSelf();
        Storage::shouldReceive('files')
            ->with('Application')
            ->andReturn($logList);

        $testObj = new LogUtilitiesService($this->action);
        $res = $testObj->getListOfLogFiles();

        $this->assertEquals(['log1', 'log2', 'log3'], $res);
    }

    /*
    |---------------------------------------------------------------------------
    | getLogSettings()
    |---------------------------------------------------------------------------
    */
    public function test_get_log_settings(): void
    {
        $testObj = new LogUtilitiesService($this->action);

        $shouldBe = [
            'days' => (int) config('logging.channels.app.days'),
            'log-level' => config('logging.channels.app.level'),
            'level-list' => $testObj->getLogLevels(),
        ];

        $res = $testObj->getLogSettings();

        $this->assertEquals($shouldBe, $res);
    }

    /*
    |---------------------------------------------------------------------------
    | updateLogSettings()
    |---------------------------------------------------------------------------
    */
    public function test_update_log_settings(): void
    {
        $data = [
            'days' => '120',
            'log_level' => 'critical',
        ];

        $testObj = new LogUtilitiesService($this->action);
        $testObj->updateLogSettings(collect($data));

        $this->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.auth.level',
            'value' => 'critical',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.app.level',
            'value' => 'critical',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.auth.days',
            'value' => '120',
        ])->assertDatabaseHas('app_settings', [
            'key' => 'logging.channels.app.days',
            'value' => '120',
        ]);
    }
}

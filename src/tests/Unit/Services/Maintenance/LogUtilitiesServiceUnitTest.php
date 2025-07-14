<?php

namespace Tests\Unit\Services\Maintenance;

use App\Enums\LogChannels;
use App\Enums\LogLevels;
use App\Exceptions\Maintenance\InvalidLogChannelException;
use App\Services\Maintenance\LogUtilitiesService;
use Carbon\Carbon;
use Tests\TestCase;

class LogUtilitiesServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | getLogLevels()
    |---------------------------------------------------------------------------
    */
    public function test_get_log_levels(): void
    {
        $shouldBe = array_column(LogLevels::cases(), 'name');

        $testObj = new LogUtilitiesService;
        $res = $testObj->getLogLevels();

        $this->assertEquals($res, $shouldBe);
    }

    /*
    |---------------------------------------------------------------------------
    | getLogChannels()
    |---------------------------------------------------------------------------
    */
    public function test_get_log_channels(): void
    {
        $shouldBe = array_column(LogChannels::cases(), 'name');

        $testObj = new LogUtilitiesService;
        $res = $testObj->getLogChannels();

        $this->assertEquals($res, $shouldBe);
    }

    /*
    |---------------------------------------------------------------------------
    | validateLogChannel()
    |---------------------------------------------------------------------------
    */
    public function test_validate_log_channel_app(): void
    {
        $testChannel = 'Application';

        $testObj = new LogUtilitiesService;
        $res = $testObj->validateLogChannel($testChannel);

        $this->assertEquals('Application', $res);
    }

    public function test_validate_log_channel_auth(): void
    {
        $testChannel = 'Authentication';

        $testObj = new LogUtilitiesService;
        $res = $testObj->validateLogChannel($testChannel);

        $this->assertEquals('Auth', $res);
    }

    public function test_validate_log_channel_fail(): void
    {
        $testChannel = 'Something';

        $this->expectException(InvalidLogChannelException::class);

        $testObj = new LogUtilitiesService;
        $res = $testObj->validateLogChannel($testChannel);

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | validateLogFile()
    |---------------------------------------------------------------------------
    */
    public function test_validate_log_file_pass(): void
    {
        $now = Carbon::now()->format('Y-m-d');
        $logFile = 'TechBench-'.$now;

        $testObj = new LogUtilitiesService;
        $res = $testObj->validateLogFile('application', $logFile);

        $this->assertEquals($res, 'Application/'.$logFile.'.log');
    }

    public function test_validate_log_file_fail(): void
    {
        $now = Carbon::now()->format('Y-m-d');
        $logFile = 'NotTechBench-'.$now;

        $testObj = new LogUtilitiesService;
        $res = $testObj->validateLogFile('application', $logFile);

        $this->assertFalse($res);
    }

    /*
    |---------------------------------------------------------------------------
    | getLogList()
    |---------------------------------------------------------------------------
    */
    public function test_get_log_list(): void
    {
        $testObj = new LogUtilitiesService;
        $res = $testObj->getLogList('application');

        $this->assertIsArray($res);
    }

    public function test_get_log_list_fail(): void
    {
        $this->expectException(InvalidLogChannelException::class);

        $testObj = new LogUtilitiesService;
        $res = $testObj->getLogList('random');

        $this->assertEmpty($res);
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

        $testObj = new LogUtilitiesService;
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

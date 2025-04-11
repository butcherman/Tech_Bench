<?php

namespace Tests\Unit\Services\Maintenance;

use App\Services\Maintenance\AppLogParsingService;
use Carbon\Carbon;
use Tests\TestCase;

class AppLogParsingServiceUnitTest extends TestCase
{
    public function test_get_log_file_data(): void
    {
        $now = Carbon::now()->format('Y-m-d');
        $logFile = 'TechBench-'.$now;

        $testObj = new AppLogParsingService;
        $res = $testObj->getLogFileData('Application', $logFile);

        $this->assertIsArray($res);
    }
}

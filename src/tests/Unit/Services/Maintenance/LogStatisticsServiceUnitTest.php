<?php

namespace Tests\Unit\Services\Maintenance;

use App\Services\Maintenance\LogStatisticsService;
use PHPUnit\Framework\TestCase;

class LogStatisticsServiceUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | add() & toArray()
    |---------------------------------------------------------------------------
    */
    public function test_add_and_to_array(): void
    {
        $entries = [
            [
                'timestamp' => '2026-09-11 10:00:00',
                'env' => 'testing',
                'level' => 'info',
                'user' => 'Zim Zeema',
                'data' => [
                    'body' => 'First Message',
                    'extra' => null,
                    'stack_trace' => [],
                    'context' => [
                        'ip_address' => '1.2.3.4',
                        'trace_id' => '12345',
                        'user' => 'Zim Zeema',
                        'user_id' => 3,
                    ],
                ],
            ],
            [
                'timestamp' => '2026-09-11 10:00:01',
                'env' => 'testing',
                'level' => 'error',
                'user' => 'Adams Apple',
                'data' => [
                    'body' => 'Second Message',
                    'extra' => null,
                    'stack_trace' => [],
                    'context' => [
                        'ip_address' => '1.2.3.5',
                        'trace_id' => '12346',
                        'user' => 'Adams Apple',
                        'user_id' => 2,
                    ],
                ],
            ],
            [
                'timestamp' => '2026-09-11 10:00:02',
                'env' => 'testing',
                'level' => 'debug',
                'user' => 'System Administrator',
                'data' => [
                    'body' => 'Third Message',
                    'extra' => null,
                    'stack_trace' => [],
                    'context' => [
                        'ip_address' => '1.2.3.4',
                        'trace_id' => '12346',
                        'user' => 'System Administrator',
                        'user_id' => 3,
                    ],
                ],
            ],
        ];

        $testObj = new LogStatisticsService;

        foreach ($entries as $entry) {
            $testObj->add($entry);
        }

        $res = $testObj->toArray();
        $this->assertEquals([
            'total' => 3,
            'levels' => [
                'emergency' => 0,
                'alert' => 0,
                'critical' => 0,
                'error' => 1,
                'warning' => 0,
                'notice' => 0,
                'info' => 1,
                'debug' => 1,
            ],
            'traceIds' => [
                '12345' => 1,
                '12346' => 2,
            ],
            'userList' => [
                'Adams Apple' => 1,
                'System Administrator' => 1,
                'Zim Zeema' => 1,
            ],
        ], $res);
    }
}

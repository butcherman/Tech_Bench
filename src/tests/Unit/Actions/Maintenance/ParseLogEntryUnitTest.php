<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\ParseLogEntry;
use Carbon\Carbon;
use PHPUnit\Framework\TestCase;

class ParseLogEntryUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        $shouldBe = [
            'timestamp' => Carbon::parse('2026-09-12 00:01:52'),
            'env' => 'local',
            'level' => 'debug',
            'data' => [
                'body' => 'Route random visited by System Administrator',
                'extra' => [
                    'url' => 'https://testbench.test/random',
                    'method' => 'GET',
                ],
                'stack_trace' => [],
                'context' => [
                    'ip_address' => '192.168.99.99',
                    'trace_id' => '6a426c32-0684-4174-96f3-7eb94595d65a',
                    'user' => 'System Administrator',
                    'user_id' => 1,
                ],
            ],
            'user' => 'System Administrator',
        ];

        $entry = '[2026-09-12 00:01:52] local.DEBUG: Route random visited by System Administrator {"url":"https://testbench.test/random","method":"GET"} {"trace_id":"6a426c32-0684-4174-96f3-7eb94595d65a","user_id":1,"user":"System Administrator","ip_address":"192.168.99.99"}';

        $testObj = new ParseLogEntry;
        $res = $testObj($entry);

        $this->assertEquals($shouldBe, $res);
    }

    public function test_invoke_without_extra_data(): void
    {
        $shouldBe = [
            'timestamp' => Carbon::parse('2026-09-12 00:01:52'),
            'env' => 'local',
            'level' => 'debug',
            'data' => [
                'body' => 'Route random visited by System Administrator',
                'extra' => null,
                'stack_trace' => [],
                'context' => [
                    'ip_address' => '192.168.99.99',
                    'trace_id' => '6a426c32-0684-4174-96f3-7eb94595d65a',
                    'user' => 'System Administrator',
                    'user_id' => 1,
                ],
            ],
            'user' => 'System Administrator',
        ];

        $entry = '[2026-09-12 00:01:52] local.DEBUG: Route random visited by System Administrator {"trace_id":"6a426c32-0684-4174-96f3-7eb94595d65a","user_id":1,"user":"System Administrator","ip_address":"192.168.99.99"}';

        $testObj = new ParseLogEntry;
        $res = $testObj($entry);

        $this->assertEquals($shouldBe, $res);
    }

    public function test_invoke_without_context(): void
    {
        $shouldBe = [
            'timestamp' => Carbon::parse('2026-09-12 00:01:52'),
            'env' => 'local',
            'level' => 'debug',
            'data' => [
                'body' => 'Route random visited by System Administrator',
                'extra' => [
                    'url' => 'https://testbench.test/random',
                    'method' => 'GET',
                ],
                'stack_trace' => [],
                'context' => null,
            ],
            'user' => null,
        ];

        $entry = '[2026-09-12 00:01:52] local.DEBUG: Route random visited by System Administrator {"url":"https://testbench.test/random","method":"GET"}';

        $testObj = new ParseLogEntry;
        $res = $testObj($entry);

        $this->assertEquals($shouldBe, $res);
    }

    public function test_invoke_without_context_or_extra_data(): void
    {
        $shouldBe = [
            'timestamp' => Carbon::parse('2026-09-12 00:01:52'),
            'env' => 'local',
            'level' => 'debug',
            'data' => [
                'body' => 'Route random visited by System Administrator',
                'extra' => null,
                'stack_trace' => [],
                'context' => null,
            ],
            'user' => null,
        ];

        $entry = '[2026-09-12 00:01:52] local.DEBUG: Route random visited by System Administrator';

        $testObj = new ParseLogEntry;
        $res = $testObj($entry);

        $this->assertEquals($shouldBe, $res);
    }

    public function test_invoke_invalid_json(): void
    {
        $shouldBe = [
            'timestamp' => Carbon::parse('2026-09-12 00:01:52'),
            'env' => 'local',
            'level' => 'debug',
            'data' => [
                'body' => 'Route random visited by System Administrator {"url": {"https://testbench.test/random" "method"}',
                'extra' => null,
                'stack_trace' => [],
                'context' => [
                    'ip_address' => '192.168.99.99',
                    'trace_id' => '6a426c32-0684-4174-96f3-7eb94595d65a',
                    'user' => 'System Administrator',
                    'user_id' => 1,
                ],
            ],
            'user' => 'System Administrator',
        ];

        $entry = '[2026-09-12 00:01:52] local.DEBUG: Route random visited by System Administrator {"url": {"https://testbench.test/random" "method"} {"trace_id":"6a426c32-0684-4174-96f3-7eb94595d65a","user_id":1,"user":"System Administrator","ip_address":"192.168.99.99"}';

        $testObj = new ParseLogEntry;
        $res = $testObj($entry);

        $this->assertEquals($shouldBe, $res);
    }

    public function test_invoke_escaped_json(): void
    {
        $shouldBe = [
            'timestamp' => Carbon::parse('2026-09-12 00:01:52'),
            'env' => 'local',
            'level' => 'debug',
            'data' => [
                'body' => 'Route random visited by System Administrator {"trace_id":"\"6a426c32-0684-4174-96f3-7eb94595d65a\"","user_id":1,"user":"System Administrator","ip_address":"192.168.99.99"}',
                'extra' => null,
                'stack_trace' => [],
                'context' => null,
            ],
            'user' => null,
        ];

        $entry = '[2026-09-12 00:01:52] local.DEBUG: Route random visited by System Administrator {"trace_id":"\\"6a426c32-0684-4174-96f3-7eb94595d65a\\"","user_id":1,"user":"System Administrator","ip_address":"192.168.99.99"}';

        $testObj = new ParseLogEntry;
        $res = $testObj($entry);

        $this->assertEquals($shouldBe, $res);
    }

    public function test_invoke_bad_entry(): void
    {
        $entry = 'invalid entry';

        $testObj = new ParseLogEntry;
        $res = $testObj($entry);

        $this->assertNull($res);
    }
}

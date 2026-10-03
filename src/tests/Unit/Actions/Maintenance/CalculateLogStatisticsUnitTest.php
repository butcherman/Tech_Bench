<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\CalculateLogStatistics;
use App\Actions\Maintenance\ParseLogEntry;
use App\Services\Maintenance\LogStatisticsService;
use App\Services\Maintenance\ReverseLogReader;
use Generator;
use Illuminate\Support\Facades\Storage;
use Mockery;
use Tests\TestCase;

class CalculateLogStatisticsUnitTest extends TestCase
{
    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_invoke(): void
    {
        Storage::fake('logs');

        $reader = Mockery::mock(ReverseLogReader::class);
        $parseEntry = Mockery::mock(ParseLogEntry::class);
        $stats = Mockery::mock(LogStatisticsService::class);

        $entries = [
            '[2026-09-11 10:00:00] local.INFO: First message',
            '[2026-09-11 10:01:00] local.ERROR: Second message',
            '[2026-09-11 10:02:00] local.WARNING: Third message',
        ];

        $parsedEntries = [
            ['level' => 'INFO'],
            ['level' => 'ERROR'],
            ['level' => 'WARNING'],
        ];

        $reader
            ->shouldReceive('entries')
            ->once()
            ->with(
                Storage::disk('logs')->path('Application/test.log'),
                null
            )
            ->andReturn($this->generator($entries));

        $parseEntry
            ->shouldReceive('__invoke')
            ->once()
            ->with($entries[0])
            ->andReturn($parsedEntries[0]);

        $parseEntry
            ->shouldReceive('__invoke')
            ->once()
            ->with($entries[1])
            ->andReturn($parsedEntries[1]);

        $parseEntry
            ->shouldReceive('__invoke')
            ->once()
            ->with($entries[2])
            ->andReturn($parsedEntries[2]);

        $stats
            ->shouldReceive('add')
            ->once()
            ->with($parsedEntries[0]);

        $stats
            ->shouldReceive('add')
            ->once()
            ->with($parsedEntries[1]);

        $stats
            ->shouldReceive('add')
            ->once()
            ->with($parsedEntries[2]);

        $stats
            ->shouldReceive('toArray')
            ->once()
            ->andReturn([
                'total' => 3,
                'levels' => [
                    'INFO' => 1,
                    'ERROR' => 1,
                    'WARNING' => 1,
                ],
            ]);

        $testObj = new CalculateLogStatistics(
            $reader,
            $parseEntry,
            $stats,
        );

        $res = $testObj('test');

        $this->assertSame([
            'total' => 3,
            'levels' => [
                'INFO' => 1,
                'ERROR' => 1,
                'WARNING' => 1,
            ],
        ], $res);
    }

    private function generator(array $entries): Generator
    {
        yield from $entries;
    }
}

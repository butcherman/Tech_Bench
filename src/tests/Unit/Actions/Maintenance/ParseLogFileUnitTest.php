<?php

namespace Tests\Unit\Actions\Maintenance;

use App\Actions\Maintenance\ParseLogEntry;
use App\Actions\Maintenance\ParseLogFile;
use App\DTO\Maintenance\LogFilter;
use App\DTO\Maintenance\LogSnapshot;
use App\Services\Maintenance\ReverseLogReader;
use Illuminate\Support\Facades\Storage;
use Mockery\Mock;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

class ParseLogFileUnitTest extends TestCase
{
    /** @var ReverseLogReader&Mock */
    private ReverseLogReader $reader;

    /** @var ParseLogEntry&Mock */
    private ParseLogEntry $parseEntry;

    /** @var ParseLogFile&Mock */
    private ParseLogFile $action;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reader = $this->mock(ReverseLogReader::class);
        $this->parseEntry = $this->mock(ParseLogEntry::class);

        $this->action = new ParseLogFile(
            $this->reader,
            $this->parseEntry,
        );
    }

    private function snapshot(int $position = 123): LogSnapshot
    {
        return new LogSnapshot(
            position: $position,
        );
    }

    private function filter(
        ?string $level = null,
        ?string $user = null,
        ?string $traceId = null,
        ?string $search = null,
    ): LogFilter {
        return new LogFilter(
            level: $level,
            user: $user,
            traceId: $traceId,
            search: $search,
        );
    }

    private function generator(array $values): \Generator
    {
        yield from $values;
    }

    /*
    |---------------------------------------------------------------------------
    | __invoke()
    |---------------------------------------------------------------------------
    */
    public function test_it_returns_parsed_log_entries(): void
    {
        Storage::fake('logs');

        $snapshot = $this->snapshot();
        $filter = $this->filter();

        $rawEntries = [
            'raw entry 1',
            'raw entry 2',
        ];

        $parsedEntries = [
            [
                'level' => 'INFO',
                'user' => 1,
                'data' => [
                    'body' => 'First entry',
                    'context' => [],
                ],
            ],
            [
                'level' => 'ERROR',
                'user' => 2,
                'data' => [
                    'body' => 'Second entry',
                    'context' => [],
                ],
            ],
        ];

        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->with(
                Storage::disk('logs')->path('Application/test.log'),
                123,
            )
            ->andReturn((function () use ($rawEntries) {
                yield from $rawEntries;
            })());

        $this->parseEntry
            ->shouldReceive('__invoke')
            ->once()
            ->with('raw entry 1')
            ->andReturn($parsedEntries[0]);

        $this->parseEntry
            ->shouldReceive('__invoke')
            ->once()
            ->with('raw entry 2')
            ->andReturn($parsedEntries[1]);

        $result = ($this->action)(
            'test',
            $snapshot,
            $filter,
            1,
        );

        $this->assertSame([
            'data' => $parsedEntries,
            'meta' => [
                'current_page' => 1,
                'from' => 1,
                'to' => 2,
                'has_more' => false,
                'snapshot' => 123,
            ],
        ], $result);
    }

    public function test_it_skips_empty_entries(): void
    {
        $snapshot = $this->snapshot();
        $filter = $this->filter();

        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn((function () {
                yield '';
                yield null;
                yield false;
                yield 'valid';
            })());

        $this->parseEntry
            ->shouldReceive('__invoke')
            ->once()
            ->with('valid')
            ->andReturn([
                'level' => 'INFO',
                'user' => null,
                'data' => [
                    'body' => 'Valid',
                    'context' => [],
                ],
            ]);

        $result = ($this->action)(
            'test',
            $snapshot,
            $filter,
            1,
        );

        $this->assertCount(1, $result['data']);
    }

    public function test_it_skips_entries_that_cannot_be_parsed(): void
    {
        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn((function () {
                yield 'invalid';
                yield 'valid';
            })());

        $this->parseEntry
            ->shouldReceive('__invoke')
            ->with('invalid')
            ->andReturn(null);

        $this->parseEntry
            ->shouldReceive('__invoke')
            ->with('valid')
            ->andReturn([
                'level' => 'INFO',
                'user' => null,
                'data' => [
                    'body' => 'Valid',
                    'context' => [],
                ],
            ]);

        $result = ($this->action)(
            'test',
            $this->snapshot(),
            $this->filter(),
            1,
        );

        $this->assertCount(1, $result['data']);
    }

    #[DataProvider('filterProvider')]
    public function test_it_filters_entries(
        LogFilter $filter,
        array $entries,
        array $expected,
    ): void {
        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn((function () use ($entries) {
                foreach ($entries as $entry) {
                    yield $entry['raw'];
                }
            })());

        foreach ($entries as $entry) {
            $this->parseEntry
                ->shouldReceive('__invoke')
                ->with($entry['raw'])
                ->andReturn($entry['parsed']);
        }

        $result = ($this->action)(
            'test',
            $this->snapshot(),
            $filter,
            1,
        );

        $this->assertSame($expected, $result['data']);
    }

    public static function filterProvider(): array
    {
        $info = [
            'level' => 'INFO',
            'user' => 10,
            'data' => [
                'body' => 'User logged in',
                'context' => [
                    'trace_id' => 'abc-123',
                    'ip_address' => '10.0.0.1',
                ],
            ],
        ];

        $error = [
            'level' => 'ERROR',
            'user' => 20,
            'data' => [
                'body' => 'Database failure',
                'context' => [
                    'trace_id' => 'def-456',
                    'ip_address' => '10.0.0.2',
                ],
            ],
        ];

        return [
            'no filters' => [
                'filter' => new LogFilter,
                'entries' => [
                    ['raw' => 'info', 'parsed' => $info],
                    ['raw' => 'error', 'parsed' => $error],
                ],
                'expected' => [$info, $error],
            ],

            'level filter' => [
                'filter' => new LogFilter(level: 'ERROR'),
                'entries' => [
                    ['raw' => 'info', 'parsed' => $info],
                    ['raw' => 'error', 'parsed' => $error],
                ],
                'expected' => [$error],
            ],

            'user filter' => [
                'filter' => new LogFilter(user: 20),
                'entries' => [
                    ['raw' => 'info', 'parsed' => $info],
                    ['raw' => 'error', 'parsed' => $error],
                ],
                'expected' => [],
            ],

            'trace id filter' => [
                'filter' => new LogFilter(traceId: 'def-456'),
                'entries' => [
                    ['raw' => 'info', 'parsed' => $info],
                    ['raw' => 'error', 'parsed' => $error],
                ],
                'expected' => [$error],
            ],

            'search filter' => [
                'filter' => new LogFilter(search: 'DATABASE'),
                'entries' => [
                    ['raw' => 'info', 'parsed' => $info],
                    ['raw' => 'error', 'parsed' => $error],
                ],
                'expected' => [$error],
            ],
        ];
    }

    public function test_pagination_is_applied_after_filtering(): void
    {
        $rawEntries = [];
        $parsedEntries = [];

        for ($i = 1; $i <= 110; $i++) {
            $raw = "raw-{$i}";

            $rawEntries[] = $raw;

            $parsedEntries[$raw] = [
                'level' => $i <= 50 ? 'INFO' : 'ERROR',
                'user' => null,
                'data' => [
                    'body' => "Entry {$i}",
                    'context' => [],
                ],
            ];
        }

        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn($this->generator($rawEntries));

        foreach ($parsedEntries as $raw => $parsed) {
            $this->parseEntry
                ->shouldReceive('__invoke')
                ->with($raw)
                ->andReturn($parsed);
        }

        $result = ($this->action)(
            'test',
            $this->snapshot(),
            new LogFilter(level: 'ERROR'),
            2,
        );

        $this->assertCount(10, $result['data']);

        $this->assertSame(
            'Entry 101',
            $result['data'][0]['data']['body'],
        );

        $this->assertSame(
            'Entry 110',
            $result['data'][9]['data']['body'],
        );

        $this->assertSame(2, $result['meta']['current_page']);
        $this->assertSame(51, $result['meta']['from']);
        $this->assertSame(60, $result['meta']['to']);
        $this->assertFalse($result['meta']['has_more']);
    }

    public function test_it_reports_no_more_pages_when_exactly_fifty_entries_exist(): void
    {
        $rawEntries = [];
        $parsedEntries = [];

        for ($i = 1; $i <= 50; $i++) {
            $raw = "raw-{$i}";

            $rawEntries[] = $raw;

            $parsedEntries[$raw] = [
                'level' => 'INFO',
                'user' => null,
                'data' => [
                    'body' => "Entry {$i}",
                    'context' => [],
                ],
            ];
        }

        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn((function () use ($rawEntries): \Generator {
                yield from $rawEntries;
            })());

        foreach ($parsedEntries as $raw => $parsed) {
            $this->parseEntry
                ->shouldReceive('__invoke')
                ->once()
                ->with($raw)
                ->andReturn($parsed);
        }

        $result = ($this->action)(
            'test',
            $this->snapshot(),
            new LogFilter,
            1,
        );

        $this->assertCount(50, $result['data']);

        $this->assertFalse($result['meta']['has_more']);
        $this->assertSame(1, $result['meta']['current_page']);
        $this->assertSame(1, $result['meta']['from']);
        $this->assertSame(50, $result['meta']['to']);
        $this->assertSame(123, $result['meta']['snapshot']);
    }

    public function test_it_reports_more_pages_when_fifty_one_entries_exist(): void
    {
        $rawEntries = [];
        $parsedEntries = [];

        for ($i = 1; $i <= 51; $i++) {
            $raw = "raw-{$i}";

            $rawEntries[] = $raw;

            $parsedEntries[$raw] = [
                'level' => 'INFO',
                'user' => null,
                'data' => [
                    'body' => "Entry {$i}",
                    'context' => [],
                ],
            ];
        }

        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn((function () use ($rawEntries): \Generator {
                yield from $rawEntries;
            })());

        foreach ($parsedEntries as $raw => $parsed) {
            $this->parseEntry
                ->shouldReceive('__invoke')
                ->once()
                ->with($raw)
                ->andReturn($parsed);
        }

        $result = ($this->action)(
            'test',
            $this->snapshot(),
            new LogFilter,
            1,
        );

        $this->assertCount(50, $result['data']);
        $this->assertTrue($result['meta']['has_more']);
        $this->assertSame(1, $result['meta']['from']);
        $this->assertSame(50, $result['meta']['to']);
    }

    public function test_page_two_starts_at_matching_entry_fifty_one(): void
    {
        $rawEntries = [];
        $parsedEntries = [];

        for ($i = 1; $i <= 101; $i++) {
            $raw = "raw-{$i}";

            $rawEntries[] = $raw;

            $parsedEntries[$raw] = [
                'level' => 'INFO',
                'user' => null,
                'data' => [
                    'body' => "Entry {$i}",
                    'context' => [],
                ],
            ];
        }

        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn((function () use ($rawEntries): \Generator {
                yield from $rawEntries;
            })());

        foreach ($parsedEntries as $raw => $parsed) {
            $this->parseEntry
                ->shouldReceive('__invoke')
                ->once()
                ->with($raw)
                ->andReturn($parsed);
        }

        $result = ($this->action)(
            'test',
            $this->snapshot(),
            new LogFilter,
            2,
        );

        $this->assertSame(2, $result['meta']['current_page']);
        $this->assertSame(51, $result['meta']['from']);
        $this->assertSame(100, $result['meta']['to']);
        $this->assertTrue($result['meta']['has_more']);
    }

    #[DataProvider('searchProvider')]
    public function test_search_matches_expected_field(
        string $search,
        array $entry,
        bool $matches,
    ): void {
        $this->reader
            ->shouldReceive('entries')
            ->once()
            ->andReturn((function () {
                yield 'raw';
            })());

        $this->parseEntry
            ->shouldReceive('__invoke')
            ->with('raw')
            ->andReturn($entry);

        $result = ($this->action)(
            'test',
            $this->snapshot(),
            new LogFilter(search: $search),
            1,
        );

        $this->assertCount($matches ? 1 : 0, $result['data']);
    }

    public static function searchProvider(): array
    {
        $entry = [
            'level' => 'ERROR',
            'user' => 42,
            'data' => [
                'body' => 'Database connection failed',
                'context' => [
                    'trace_id' => 'ABC-123',
                    'ip_address' => '192.168.1.50',
                ],
            ],
        ];

        return [
            'body' => ['connection', $entry, true],
            'user' => ['42', $entry, true],
            'trace id' => ['abc-123', $entry, true],
            'ip address' => ['192.168.1.50', $entry, true],
            'case insensitive' => ['DATABASE', $entry, true],
            'not found' => ['redis', $entry, false],
        ];
    }
}

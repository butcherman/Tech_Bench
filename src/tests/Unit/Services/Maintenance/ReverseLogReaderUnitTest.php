<?php

namespace Tests\Unit\Services\Maintenance;

use App\Services\Maintenance\ReverseLogReader;
use Generator;
use PHPUnit\Framework\TestCase;
use RuntimeException;

class ReverseLogReaderUnitTest extends TestCase
{
    private ReverseLogReader $reader;

    private string $path;

    protected function setUp(): void
    {
        parent::setUp();

        $this->reader = new ReverseLogReader;

        $path = tempnam(sys_get_temp_dir(), 'reverse-log-');

        $this->assertNotFalse($path);

        $this->path = $path;
    }

    protected function tearDown(): void
    {
        if (file_exists($this->path)) {
            unlink($this->path);
        }

        parent::tearDown();
    }

    public function test_it_returns_a_generator(): void
    {
        file_put_contents(
            $this->path,
            '[2026-09-12 10:00:00] local.INFO: Test'
        );

        $entries = $this->reader->entries($this->path);

        $this->assertInstanceOf(Generator::class, $entries);
    }

    public function test_it_throws_when_the_log_file_cannot_be_opened(): void
    {
        $path = sys_get_temp_dir().'/reverse-log-does-not-exist-'.uniqid().'.log';

        $this->expectException(RuntimeException::class);

        set_error_handler(
            static fn (): bool => true,
            E_WARNING
        );

        try {
            $this->reader->entries($path)->current();
        } finally {
            restore_error_handler();
        }
    }

    public function test_it_returns_no_entries_for_an_empty_file(): void
    {
        file_put_contents($this->path, '');

        $entries = $this->readEntries();

        $this->assertSame([], $entries);
    }

    public function test_it_reads_a_single_entry(): void
    {
        $entry = '[2026-09-12 10:00:00] local.INFO: Only entry';

        file_put_contents($this->path, $entry);

        $entries = $this->readEntries();

        $this->assertSame(
            [$entry],
            $entries
        );
    }

    public function test_it_reads_entries_from_newest_to_oldest(): void
    {
        file_put_contents($this->path, implode("\n", [
            '[2026-09-12 10:00:00] local.INFO: First',
            '[2026-09-12 10:01:00] local.INFO: Second',
            '[2026-09-12 10:02:00] local.INFO: Third',
        ]));

        $entries = $this->readEntries();

        $this->assertSame([
            '[2026-09-12 10:02:00] local.INFO: Third',
            '[2026-09-12 10:01:00] local.INFO: Second',
            '[2026-09-12 10:00:00] local.INFO: First',
        ], $entries);
    }

    public function test_it_handles_a_trailing_newline(): void
    {
        file_put_contents($this->path, implode("\n", [
            '[2026-09-12 10:00:00] local.INFO: First',
            '[2026-09-12 10:01:00] local.INFO: Second',
            '',
        ]));

        $entries = $this->readEntries();

        $this->assertSame([
            '[2026-09-12 10:01:00] local.INFO: Second',
            '[2026-09-12 10:00:00] local.INFO: First',
        ], $entries);
    }

    public function test_it_handles_a_file_without_a_trailing_newline(): void
    {
        file_put_contents($this->path, implode("\n", [
            '[2026-09-12 10:00:00] local.INFO: First',
            '[2026-09-12 10:01:00] local.INFO: Second',
        ]));

        $entries = $this->readEntries();

        $this->assertSame([
            '[2026-09-12 10:01:00] local.INFO: Second',
            '[2026-09-12 10:00:00] local.INFO: First',
        ], $entries);
    }

    public function test_it_handles_windows_line_endings(): void
    {
        file_put_contents($this->path, implode("\r\n", [
            '[2026-09-12 10:00:00] local.INFO: First',
            '[2026-09-12 10:01:00] local.INFO: Second',
        ]));

        $entries = $this->readEntries();

        $this->assertSame([
            '[2026-09-12 10:01:00] local.INFO: Second',
            '[2026-09-12 10:00:00] local.INFO: First',
        ], $entries);
    }

    public function test_it_keeps_stack_traces_attached_to_their_log_entry(): void
    {
        file_put_contents($this->path, <<<'LOG'
[2026-09-12 10:00:00] local.INFO: Normal entry
[2026-09-12 10:01:00] local.ERROR: Something failed
#0 /var/www/html/app/Foo.php(123): App\Foo->bar()
#1 /var/www/html/app/Bar.php(456): App\Bar->foo()
#2 {main}
[2026-09-12 10:02:00] local.INFO: Following entry
LOG
        );

        $entries = $this->readEntries();

        $this->assertCount(3, $entries);

        $this->assertSame(
            '[2026-09-12 10:02:00] local.INFO: Following entry',
            $entries[0]
        );

        $this->assertSame(implode("\n", [
            '[2026-09-12 10:01:00] local.ERROR: Something failed',
            '#0 /var/www/html/app/Foo.php(123): App\Foo->bar()',
            '#1 /var/www/html/app/Bar.php(456): App\Bar->foo()',
            '#2 {main}',
        ]), $entries[1]);

        $this->assertSame(
            '[2026-09-12 10:00:00] local.INFO: Normal entry',
            $entries[2]
        );
    }

    public function test_it_handles_multiple_stack_traces_independently(): void
    {
        file_put_contents($this->path, <<<'LOG'
[2026-09-12 10:00:00] local.ERROR: First failure
#0 /app/First.php(10): foo()
#1 {main}
[2026-09-12 10:01:00] local.INFO: Normal entry
[2026-09-12 10:02:00] local.ERROR: Second failure
#0 /app/Second.php(20): bar()
#1 {main}
LOG
        );

        $entries = $this->readEntries();

        $this->assertCount(3, $entries);

        $this->assertSame(implode("\n", [
            '[2026-09-12 10:02:00] local.ERROR: Second failure',
            '#0 /app/Second.php(20): bar()',
            '#1 {main}',
        ]), $entries[0]);

        $this->assertSame(
            '[2026-09-12 10:01:00] local.INFO: Normal entry',
            $entries[1]
        );

        $this->assertSame(implode("\n", [
            '[2026-09-12 10:00:00] local.ERROR: First failure',
            '#0 /app/First.php(10): foo()',
            '#1 {main}',
        ]), $entries[2]);
    }

    public function test_it_handles_an_entry_that_spans_multiple_chunks(): void
    {
        $largeMessage = str_repeat('A', 70_000);

        file_put_contents($this->path, implode("\n", [
            '[2026-09-12 10:00:00] local.INFO: First',
            '[2026-09-12 10:01:00] local.INFO: '.$largeMessage,
            '[2026-09-12 10:02:00] local.INFO: Last',
        ]));

        $entries = $this->readEntries();

        $this->assertCount(3, $entries);

        $this->assertSame(
            '[2026-09-12 10:02:00] local.INFO: Last',
            $entries[0]
        );

        $this->assertSame(
            '[2026-09-12 10:01:00] local.INFO: '.$largeMessage,
            $entries[1]
        );

        $this->assertSame(
            '[2026-09-12 10:00:00] local.INFO: First',
            $entries[2]
        );
    }

    public function test_it_handles_a_stack_trace_that_spans_multiple_chunks(): void
    {
        $trace = implode("\n", array_map(
            fn (int $i): string => "#{$i} /var/www/html/app/File{$i}.php({$i}): Foo::bar()",
            range(0, 500)
        ));

        file_put_contents($this->path, implode("\n", [
            '[2026-09-12 10:00:00] local.INFO: First',
            '[2026-09-12 10:01:00] local.ERROR: Exception occurred',
            $trace,
            '[2026-09-12 10:02:00] local.INFO: Last',
        ]));

        $entries = $this->readEntries();

        $this->assertCount(3, $entries);

        $this->assertSame(
            '[2026-09-12 10:02:00] local.INFO: Last',
            $entries[0]
        );

        $this->assertStringContainsString(
            '[2026-09-12 10:01:00] local.ERROR: Exception occurred',
            $entries[1]
        );

        $this->assertStringContainsString(
            '#0 /var/www/html/app/File0.php',
            $entries[1]
        );

        $this->assertStringContainsString(
            '#500 /var/www/html/app/File500.php',
            $entries[1]
        );

        $this->assertSame(
            '[2026-09-12 10:00:00] local.INFO: First',
            $entries[2]
        );
    }

    public function test_it_does_not_yield_a_stack_trace_as_a_separate_entry(): void
    {
        file_put_contents($this->path, <<<'LOG'
[2026-09-12 10:00:00] local.INFO: Before
[2026-09-12 10:01:00] local.ERROR: Error
#0 /app/Foo.php(10): Foo->bar()
#1 /app/Bar.php(20): Bar->foo()
#2 {main}
[2026-09-12 10:02:00] local.INFO: After
LOG
        );

        $entries = $this->readEntries();

        $this->assertCount(3, $entries);

        $this->assertSame(
            '[2026-09-12 10:02:00] local.INFO: After',
            $entries[0]
        );

        $this->assertSame(implode("\n", [
            '[2026-09-12 10:01:00] local.ERROR: Error',
            '#0 /app/Foo.php(10): Foo->bar()',
            '#1 /app/Bar.php(20): Bar->foo()',
            '#2 {main}',
        ]), $entries[1]);

        $this->assertSame(
            '[2026-09-12 10:00:00] local.INFO: Before',
            $entries[2]
        );

        $this->assertNotContains(
            '#2 {main}',
            $entries
        );
    }

    public function test_it_preserves_blank_lines_inside_an_entry(): void
    {
        file_put_contents($this->path, <<<'LOG'
[2026-09-12 10:00:00] local.ERROR: Error message
First line

Third line
[2026-09-12 10:01:00] local.INFO: Next entry
LOG
        );

        $entries = $this->readEntries();

        $this->assertCount(2, $entries);

        $this->assertSame(
            '[2026-09-12 10:01:00] local.INFO: Next entry',
            $entries[0]
        );

        $this->assertSame(implode("\n", [
            '[2026-09-12 10:00:00] local.ERROR: Error message',
            'First line',
            '',
            'Third line',
        ]), $entries[1]);
    }

    public function test_it_handles_structured_json_context(): void
    {
        file_put_contents($this->path, <<<'LOG'
[2026-09-12 10:00:00] local.INFO: First entry {"trace_id":"abc123","user":null}
[2026-09-12 10:01:00] local.DEBUG: Route login visited {"trace_id":"def456","user":null,"route":"login"}
LOG
        );

        $entries = $this->readEntries();

        $this->assertCount(2, $entries);

        $this->assertStringContainsString(
            '"trace_id":"def456"',
            $entries[0]
        );

        $this->assertStringContainsString(
            '"trace_id":"abc123"',
            $entries[1]
        );
    }

    public function test_it_starts_reading_from_the_supplied_end_position(): void
    {
        $content = implode("\n", [
            '[2026-09-12 10:00:00] local.INFO: First',
            '[2026-09-12 10:01:00] local.INFO: Second',
            '[2026-09-12 10:02:00] local.INFO: Third',
        ]);

        file_put_contents($this->path, $content);

        $endPosition = strpos(
            $content,
            '[2026-09-12 10:02:00]'
        );

        $this->assertNotFalse($endPosition);

        $entries = $this->readEntries(
            $this->reader,
            $this->path,
            $endPosition
        );

        $this->assertSame([
            '[2026-09-12 10:01:00] local.INFO: Second',
            '[2026-09-12 10:00:00] local.INFO: First',
        ], $entries);
    }

    /**
     * @return list<string>
     */
    private function readEntries(
        ?ReverseLogReader $reader = null,
        ?string $path = null,
        ?int $endPosition = null,
    ): array {
        return iterator_to_array(
            ($reader ?? $this->reader)->entries(
                $path ?? $this->path,
                $endPosition
            )
        );
    }
}

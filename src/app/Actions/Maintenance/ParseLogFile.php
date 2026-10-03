<?php

namespace App\Actions\Maintenance;

use App\DTO\Maintenance\LogFilter;
use App\DTO\Maintenance\LogSnapshot;
use App\Services\Maintenance\ReverseLogReader;
use Illuminate\Support\Facades\Storage;

class ParseLogFile
{
    private const PER_PAGE = 50;

    public function __construct(
        protected ReverseLogReader $reader,
        protected ParseLogEntry $parseEntry,
    ) {}

    public function __invoke(
        string $logFile,
        LogSnapshot $snapshot,
        LogFilter $filter,
        int $page,
    ): array {
        $path = Storage::disk('logs')->path(
            'Application/'.$logFile.'.log'
        );

        $skip = ($page - 1) * self::PER_PAGE;

        $entries = [];
        $matched = 0;

        foreach ($this->reader->entries($path, $snapshot->position) as $entry) {
            if (! $entry) {
                continue;
            }

            $parsed = ($this->parseEntry)($entry);

            if ($parsed === null) {
                continue;
            }

            if (! $this->matchesFilter($parsed, $filter)) {
                continue;
            }

            // This is now the position among MATCHING entries.
            if ($matched++ < $skip) {
                continue;
            }

            $entries[] = $parsed;

            // Fetch one extra so we can determine whether another
            // page exists.
            if (count($entries) > self::PER_PAGE) {
                break;
            }
        }

        $hasMore = count($entries) > self::PER_PAGE;

        if ($hasMore) {
            array_pop($entries);
        }

        return [
            'data' => $entries,
            'meta' => [
                'current_page' => $page,
                'from' => $entries === []
                    ? null
                    : $skip + 1,
                'to' => $skip + count($entries),
                'has_more' => $hasMore,
                'snapshot' => $snapshot->position,
            ],
        ];
    }

    private function matchesFilter(array $entry, LogFilter $filter): bool
    {
        if (! $filter->hasFilters()) {
            return true;
        }

        if (
            $filter->level !== null &&
            ($entry['level'] ?? null) !== $filter->level
        ) {
            return false;
        }

        if (
            $filter->user !== null &&
            ($entry['user'] ?? null) !== $filter->user
        ) {
            return false;
        }

        if (
            $filter->traceId !== null &&
            ($entry['data']['context']['trace_id'] ?? null) !== $filter->traceId
        ) {
            return false;
        }

        if ($filter->search !== null && ! $this->matchesSearch($entry, $filter->search)) {
            return false;
        }

        return true;
    }

    private function matchesSearch(array $entry, string $search): bool
    {
        $search = mb_strtolower($search);

        $values = [
            $entry['data']['body'] ?? '',
            $entry['user'] ?? '',
            $entry['data']['context']['trace_id'] ?? '',
            $entry['data']['context']['ip_address'] ?? '',
        ];

        foreach ($values as $value) {
            if (
                $value !== null &&
                str_contains(
                    mb_strtolower((string) $value),
                    $search,
                )
            ) {
                return true;
            }
        }

        return false;
    }
}

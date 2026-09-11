<?php

namespace App\Actions\Maintenance;

use App\Services\Maintenance\ReverseLogReader;
use Illuminate\Support\Facades\Storage;

class ParseLogFile
{
    private const PER_PAGE = 100;

    public function __construct(
        protected ReverseLogReader $reader,
        protected ParseLogEntry $parseEntry,
    ) {}

    public function __invoke(
        string $logFile,
        int $page,
        int $endPosition,
    ): array {
        $path = Storage::disk('logs')->path('Application/'.$logFile.'.log');

        $skip = ($page - 1) * self::PER_PAGE;

        $entries = [];
        $position = 0;

        foreach ($this->reader->entries($path, $endPosition) as $entry) {
            if ($position++ < $skip || ! $entry) {
                continue;
            }

            $parsed = ($this->parseEntry)($entry);

            if ($parsed !== null) {
                $entries[] = $parsed;
            }

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
                'snapshot' => $endPosition,
            ],
        ];
    }
}

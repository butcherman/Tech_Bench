<?php

namespace App\Actions\Maintenance;

use App\Services\Maintenance\ReverseLogReader;
use Illuminate\Support\Facades\Storage;

class ParseLogFile
{
    public function __construct(
        protected ReverseLogReader $reader,
        protected ParseLogEntry $parseEntry,
    ) {}

    public function __invoke(
        string $logFile,
        int $page = 1,
        int $perPage = 100,
    ): array {
        $path = Storage::disk('logs')->path('Application/'.$logFile.'.log');

        $skip = ($page - 1) * $perPage;

        $entries = [];
        $position = 0;

        foreach ($this->reader->entries($path) as $entry) {
            if ($position++ < $skip || ! $entry) {
                continue;
            }

            $parsed = ($this->parseEntry)($entry);

            if ($parsed !== null) {
                $entries[] = $parsed;
            }

            if (count($entries) > $perPage) {
                break;
            }
        }

        $hasMore = count($entries) > $perPage;

        if ($hasMore) {
            array_pop($entries);
        }

        return [
            'data' => $entries,
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'from' => $entries === []
                    ? null
                    : $skip + 1,
                'to' => $skip + count($entries),
                'has_more' => $hasMore,
            ],
        ];
    }
}

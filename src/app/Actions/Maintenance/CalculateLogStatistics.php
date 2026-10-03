<?php

namespace App\Actions\Maintenance;

use App\DTO\Maintenance\LogSnapshot;
use App\Services\Maintenance\LogStatisticsService;
use App\Services\Maintenance\ReverseLogReader;
use Illuminate\Support\Facades\Storage;

class CalculateLogStatistics
{
    public function __construct(
        protected ReverseLogReader $reader,
        protected ParseLogEntry $parseEntry,
        protected LogStatisticsService $stats
    ) {}

    public function __invoke(string $logFile, ?LogSnapshot $snapshot = null): array
    {
        $path = Storage::disk('logs')->path('Application/'.$logFile.'.log');

        foreach ($this->reader->entries($path, $snapshot?->position) as $entry) {
            $this->stats->add(($this->parseEntry)($entry));
        }

        return $this->stats->toArray();
    }
}

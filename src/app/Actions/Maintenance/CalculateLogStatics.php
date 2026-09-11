<?php

namespace App\Actions\Maintenance;

use App\Services\Maintenance\LogStatisticsService;
use App\Services\Maintenance\ReverseLogReader;
use Illuminate\Support\Facades\Storage;

class CalculateLogStatics
{
    public function __construct(
        protected ReverseLogReader $reader,
        protected ParseLogEntry $parseEntry,
        protected LogStatisticsService $stats
    ) {}

    public function __invoke(string $logFile)
    {
        $path = Storage::disk('logs')->path('Application/'.$logFile.'.log');

        foreach ($this->reader->entries($path) as $entry) {
            $this->stats->add(($this->parseEntry)($entry));
        }

        return $this->stats->toArray();
    }
}

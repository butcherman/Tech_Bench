<?php

namespace App\Services\Maintenance;

use Illuminate\Support\Arr;

class LogStatisticsService
{
    private int $total = 0;

    /** @var array<string, int> */
    private array $levels = [
        'emergency' => 0,
        'alert' => 0,
        'critical' => 0,
        'error' => 0,
        'warning' => 0,
        'notice' => 0,
        'info' => 0,
        'debug' => 0,
    ];

    /** @var array<string, int> */
    private array $traceIds = [];

    public function add(array $entry): void
    {
        $this->total++;

        $this->levels[$entry['level']] =
            ($this->levels[$entry['level']] ?? 0) + 1;

        if (Arr::has($entry, 'data.context.trace_id')) {
            $traceId = $entry['data']['context']['trace_id'];
            $this->traceIds[$traceId] = ($this->traceIds[$traceId] ?? 0) + 1;
        }
    }

    public function toArray(): array
    {
        return [
            'total' => $this->total,
            'levels' => $this->levels,
            'trace_ids' => $this->traceIds,
        ];
    }
}

<?php

namespace App\DTO\Maintenance;

use Carbon\Carbon;
use Carbon\CarbonImmutable;

final readonly class BackupSummary
{
    public function __construct(
        public string $name,
        public int $size,
        public CarbonImmutable $modified,
    ) {}

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'size' => $this->size,
            'modified' => Carbon::createFromTimestamp(
                $this->modified->timestamp,
                config('app.timezone')
            )->format('M d, Y h:m A'),
        ];
    }
}

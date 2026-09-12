<?php

namespace App\DTO\Maintenance;

final readonly class LogFilter
{
    /**
     * Class for holding filter information for the log file
     */
    public function __construct(
        public ?string $search = null,
        public ?string $level = null,
        public ?string $user = null,
        public ?string $traceId = null,
    ) {}

    public function hasFilters(): bool
    {
        return $this->search !== null
            || $this->level !== null
            || $this->user !== null
            || $this->traceId !== null;
    }
}

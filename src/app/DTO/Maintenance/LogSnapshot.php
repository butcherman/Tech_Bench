<?php

namespace App\DTO\Maintenance;

final readonly class LogSnapshot
{
    /**
     * Hold a snapshot of the current log file.
     */
    public function __construct(public int $position) {}
}

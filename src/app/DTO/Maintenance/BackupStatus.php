<?php

namespace App\DTO\Maintenance;

use App\Models\BackupRun;

/**
 * @codeCoverageIgnore
 */
final readonly class BackupStatus
{
    public function __construct(
        public bool $healthy,
        public ?BackupRun $latest,
        public int $backupCount,
        public int $totalSize,
        public ?string $message = null,
    ) {}

    public function toArray(): array
    {
        return [
            'healthy' => $this->healthy,
            'latest' => $this->latest?->toArray(),
            'backup_count' => $this->backupCount,
            'total_size' => $this->totalSize,
            'message' => $this->message,
        ];
    }
}

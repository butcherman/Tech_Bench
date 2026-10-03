<?php

namespace App\DTO\Maintenance;

final readonly class RestoreTransaction
{
    public function __construct(
        public string $id,
        public string $backupName,
        public string $path,
    ) {}

    public function extractedPath(): string
    {
        return $this->path.'/extracted';
    }

    public function rollbackPath(): string
    {
        return $this->path.'/rollback';
    }

    public function rollbackDatabasePath(): string
    {
        return $this->rollbackPath().'/database.sql';
    }

    public function rollbackStoragePath(): string
    {
        return $this->rollbackPath().'/storage';
    }
}

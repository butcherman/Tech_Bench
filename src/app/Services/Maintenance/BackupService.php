<?php

namespace App\Services\Maintenance;

use App\DTO\Maintenance\BackupSummary;
use App\Exceptions\Maintenance\BackupFileMissingException;
use Carbon\Carbon;
use Carbon\CarbonImmutable;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;

class BackupService
{
    /** @var Storage */
    protected $storage;

    protected string $backupBaseName;

    public function __construct()
    {
        $this->storage = Storage::disk('backups');

        $this->backupBaseName = trim(
            config('backup.backup.name'),
            DIRECTORY_SEPARATOR,
        ).DIRECTORY_SEPARATOR;
    }

    /**
     * Get all Tech Bench backups.
     */
    public function all(): Collection
    {
        return collect($this->storage->files(
            rtrim($this->backupBaseName, DIRECTORY_SEPARATOR)
        ))
            ->map(fn (string $path) => $this->makeSummary($path))
            ->sortByDesc(fn (BackupSummary $backup) => $backup->modified)
            ->values();
    }

    /**
     * Get the most recent backups.
     */
    public function recent(int $limit = 10): Collection
    {
        return $this->all()->take($limit)->values();
    }

    public function latest(): ?BackupSummary
    {
        return $this->recent(1)->first();
    }

    public function count(): int
    {
        return $this->all()->count();
    }

    public function totalSize(): int
    {
        return $this->all()->sum(
            fn (BackupSummary $backup) => $backup->size
        );
    }

    public function exists(string $backupName): bool
    {
        return $this->storage->exists(
            $this->backupPath($backupName)
        );
    }

    public function delete(string $backupName): void
    {
        $this->ensureExists($backupName);

        $this->storage->delete(
            $this->backupPath($backupName)
        );
    }

    public function path(string $backupName): string
    {
        $this->ensureExists($backupName);

        return $this->storage->path(
            $this->backupPath($backupName)
        );
    }

    public function getNextScheduledBackup(): string
    {
        if (! config('backup.nightly_backup')) {
            return 'Never';
        }

        $now = Carbon::now();
        $next3am = Carbon::today()->setTime(3, 0, 0);

        if ($now->gte($next3am)) {
            $next3am->addDay();
        }

        return $next3am->format('M d, Y h:00 A');
    }

    public function getRetentionPolicy()
    {
        $strategy = config('backup.cleanup.default_strategy');

        return [
            'daily' => $strategy['keep_daily_backups_for_days'],
            'weekly' => $strategy['keep_weekly_backups_for_weeks'],
            'monthly' => $strategy['keep_monthly_backups_for_months'],
            'yearly' => $strategy['keep_yearly_backups_for_years'],
        ];
    }

    protected function ensureExists(string $backupName): void
    {
        if (! $this->exists($backupName)) {
            throw new BackupFileMissingException($backupName);
        }
    }

    protected function backupPath(string $backupName): string
    {
        return $this->backupBaseName.$backupName;
    }

    protected function makeSummary(string $path): BackupSummary
    {
        return new BackupSummary(
            name: basename($path),
            size: $this->storage->size($path),
            modified: CarbonImmutable::createFromTimestamp(
                $this->storage->lastModified($path)
            ),
        );
    }
}

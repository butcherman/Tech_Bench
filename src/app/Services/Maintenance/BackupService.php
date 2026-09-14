<?php

namespace App\Services\Maintenance;

use App\Exceptions\Maintenance\BackupFileMissingException;
use App\Models\BackupRun;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection as EloquentCollection;
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
    public function all(): EloquentCollection
    {
        return BackupRun::all()->sortBy('completed_at')->sortDesc();
    }

    /**
     * Get a list of the most recent backups.
     */
    public function recent(int $limit = 10): EloquentCollection
    {
        return $this->all()->take($limit);
    }

    /**
     * Get the last backup that was ran
     */
    public function latest(): ?BackupRun
    {
        return $this->recent(1)->first();
    }

    /**
     * Count how many backups exist
     */
    public function count(): int
    {
        return $this->all()->count();
    }

    /**
     * Get the amount of disk space being used by the backup files
     */
    public function totalSize(): int
    {
        return $this->all()->sum(
            fn ($backup) => $backup->size
        );
    }

    /**
     * Determine if a backup file actually exists
     */
    public function exists(string $backupName): bool
    {
        return $this->storage->exists(
            $this->backupPath($backupName)
        );
    }

    /**
     * Delete a backup file and its associated DB record
     */
    public function delete(string $backupName): void
    {
        $this->ensureExists($backupName);

        BackupRun::where('backup_name', $backupName)->delete();

        $this->storage->delete(
            $this->backupPath($backupName)
        );
    }

    /**
     * Get the full path that the file belongs to.
     */
    public function path(string $backupName): string
    {
        $this->ensureExists($backupName);

        return $this->storage->path(
            $this->backupPath($backupName)
        );
    }

    /**
     * Detemine the next time an automated backup will be ran
     */
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

    /**
     * Show the saved retention policy
     */
    public function getRetentionPolicy(): array
    {
        $strategy = config('backup.cleanup.default_strategy');

        return [
            'daily' => $strategy['keep_daily_backups_for_days'],
            'weekly' => $strategy['keep_weekly_backups_for_weeks'],
            'monthly' => $strategy['keep_monthly_backups_for_months'],
            'yearly' => $strategy['keep_yearly_backups_for_years'],
        ];
    }

    /**
     * Make sure file exists, throw exception if missing.
     */
    protected function ensureExists(string $backupName): void
    {
        if (! $this->exists($backupName)) {
            throw new BackupFileMissingException($backupName);
        }
    }

    /**
     * Get the relative path of a backup file.
     */
    protected function backupPath(string $backupName): string
    {
        return $this->backupBaseName.$backupName;
    }
}

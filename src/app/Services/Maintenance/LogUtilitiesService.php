<?php

namespace App\Services\Maintenance;

use App\Actions\Maintenance\ParseLogFile;
use App\DTO\Maintenance\LogFilter;
use App\DTO\Maintenance\LogSnapshot;
use App\Enums\LogLevels;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Storage;
use RuntimeException;

class LogUtilitiesService
{
    use AppSettingsTrait;

    public function __construct(protected ParseLogFile $parseLogFile) {}

    /**
     * Return the possible log levels.
     */
    public function getLogLevels(): array
    {
        return array_column(LogLevels::cases(), 'name');
    }

    /**
     * Get the path of the log file
     */
    public function getLogFilePath(string $filename): string
    {
        $folder = 'Application';
        $relativePath = $folder.DIRECTORY_SEPARATOR.$filename.'.log';

        return Storage::disk('logs')->path($relativePath);
    }

    /**
     * Validate a specific log file exists
     */
    public function validateLogFile(string $filename): string|bool
    {
        $folder = 'Application';
        $relativePath = $folder.DIRECTORY_SEPARATOR.$filename.'.log';

        if (! Storage::disk('logs')->exists($relativePath)) {
            return false;
        }

        return true;
    }

    /**
     * Get a select number of entries from a log file, query filtering is included
     */
    public function query(string $logFile, LogSnapshot $snapshot, LogFilter $filter, int $page = 1): array
    {
        return ($this->parseLogFile)(
            $logFile,
            $snapshot,
            $filter,
            $page,
        );
    }

    /**
     * Make a snapshot of the log file as it currently sits
     */
    public function snapshot(string $logFile): LogSnapshot
    {
        $size = filesize($this->getLogFilePath($logFile));

        if ($size === false) {
            throw new RuntimeException('Unable to determine log file size: '.$logFile);
        }

        return new LogSnapshot($size);
    }

    /**
     * Fill out the Log filter object and return it
     */
    // public function getLogFilters(string $queryString)

    /**
     * Get a list of available log files
     */
    public function getListOfLogFiles(): array
    {
        $fileList = Storage::disk('logs')->files('Application');
        $logList = Arr::where($fileList, function ($value) {
            $pathInfo = pathinfo($value);

            return $pathInfo['extension'] === 'log';
        });

        return Arr::map($logList, function ($logFile) {
            $pathInfo = pathinfo($logFile);

            return $pathInfo['filename'];
        });
    }

    /*
    |---------------------------------------------------------------------------
    | Log Settings
    |---------------------------------------------------------------------------
    */

    /**
     * Get the Log Settings
     */
    public function getLogSettings(): array
    {
        return [
            'days' => (int) config('logging.channels.app.days'),
            'log-level' => config('logging.channels.app.level'),
            'level-list' => $this->getLogLevels(),
        ];
    }

    /**
     * Save the Log Settings
     */
    public function updateLogSettings(Collection $settings): void
    {
        $this->saveSettings(
            'logging.channels.app.days',
            $settings->get('days')
        );
        $this->saveSettings(
            'logging.channels.app.level',
            $settings->get('log_level')
        );

        $this->saveSettings(
            'logging.channels.auth.days',
            $settings->get('days')
        );
        $this->saveSettings(
            'logging.channels.auth.level',
            $settings->get('log_level')
        );
    }
}

<?php

namespace App\Services\Maintenance;

use App\Enums\LogChannels;
use App\Enums\LogLevels;
use App\Exceptions\Maintenance\InvalidLogChannelException;
use App\Traits\AppSettingsTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LogUtilitiesService
{
    use AppSettingsTrait;

    /**
     * Log channels that normal logs are written to.
     *
     * @var array<int, string>
     */
    protected $logChannels = ['Application', 'Authentication'];

    /**
     * Return the possible log levels.
     */
    public function getLogLevels(): array
    {
        return array_column(LogLevels::cases(), 'name');
    }

    /**
     * Return the possible log Channels
     */
    public function getLogChannels(): array
    {
        return array_column(LogChannels::cases(), 'name');
    }

    /**
     * Validate that a log channel exists and return the folder the log files
     * are in
     */
    public function validateLogChannel(string $channel): string|false
    {
        $valid = LogChannels::tryFrom(strtolower($channel));

        if (! $valid) {
            throw new InvalidLogChannelException;
        }

        return $valid->getFolder();
    }

    /**
     * Validate a specific log file exists
     */
    public function validateLogFile(string $channel, string $filename): string|bool
    {
        $folder = $this->validateLogChannel($channel);
        $relativePath = $folder.DIRECTORY_SEPARATOR.$filename.'.log';

        if (! Storage::disk('logs')->exists($relativePath)) {
            return false;
        }

        return $relativePath;
    }

    /**
     * Get a list of log files for the selected channel
     */
    public function getLogList(string $channel): array
    {
        $folder = $this->validateLogChannel($channel);

        return $this->getLogFiles($folder);
    }

    /**
     * Save the Log Settings
     */
    public function updateLogSettings(Collection $settings): void
    {
        $this->saveSettings(
            'logging.channels.daily.days',
            $settings->get('days')
        );
        $this->saveSettings(
            'logging.channels.daily.level',
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

    /**
     * Get a list of files from a folder in the Logs Directory and parse
     * to only show the .log files
     */
    protected function getLogFiles(string $folder): array
    {
        $fileList = Storage::disk('logs')->files($folder);
        $logList = Arr::where($fileList, function ($value) {
            $pathInfo = pathinfo($value);

            return $pathInfo['extension'] === 'log';
        });

        return Arr::map($logList, function ($logFile) {
            $pathInfo = pathinfo($logFile);

            return $pathInfo['filename'];
        });
    }

    /**
     * Return a log file as an array of entries
     */
    protected function getLogFileArray(string $relativePath): array
    {
        return file(Storage::disk('logs')->path($relativePath));
    }
}

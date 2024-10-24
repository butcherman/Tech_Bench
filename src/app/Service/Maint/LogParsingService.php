<?php

namespace App\Service\Maint;

use App\Exceptions\Maintenance\LogFileMissingException;
use Carbon\Carbon;

class LogParsingService extends LogUtilitiesService
{
    /**
     * Regex pattern for all application log entries
     */
    protected $appLogEntryPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*?)(\{.*?\})? .?$/i';

    /**
     * Reges pattern for application errors with Stack Trace
     */
    protected $appLogErrorPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*)?$/i';

    /**
     * Validate that a log file actually exists
     */
    public function validateLogFile(string $channel, string $fileName)
    {
        $this->validateLogChannel($channel);

        if ($this->storage->missing($channel.DIRECTORY_SEPARATOR.$fileName.'.log')) {
            throw new LogFileMissingException($fileName);
        }

        return true;
    }

    /**
     * Get log file and parse its data
     */
    public function getLogFileData(string $channel, string $fileName)
    {
        return match ($this->getChannelType($channel)) {
            'app' => $this->getAppLogFileData($channel, $fileName),
        };
    }

    /***************************************************************************
     * Application Log Files
     ***************************************************************************/

    /**
     * Parse the Application Log File
     */
    protected function getAppLogFileData(string $channel, string $fileName)
    {
        $filePath = $channel.DIRECTORY_SEPARATOR.$fileName.'.log';
        $fileArray = $this->getFileArray($filePath);
        $stats = $this->getCleanAppStats();
        $entries = [];

        // Cycle through each line of log file and parse it
        foreach ($fileArray as $line) {
            $stats = $this->buildAppLogLineStats($line, $stats);
            $parsed = $this->parseAppLine($line);
            // If parsed fails, then we are dealing with a Stack Trace
            if (! $parsed) {
                $entries[array_key_last($entries)]['stack_trace'][] = $line;
            } else {
                $entries[] = $parsed;
            }
        }

        return [
            'levels' => $this->getLogLevels(),
            'channel' => $channel,
            'filename' => $fileName,
            'file-stats' => [$stats],
            'file-entries' => $entries,
        ];
    }

    /**
     * Parse an individual log line
     */
    protected function parseAppLine(string $line)
    {
        // If this is a standard entry line, we will return normal data
        if (preg_match($this->appLogEntryPattern, $line, $data)) {
            return [
                'date' => $data[1],
                'time' => Carbon::parse($data[2])
                    ->setTimezone(config('app.timezone'))
                    ->format('m/d h:mA'),
                'env' => $data[3],
                'level' => $data[4],
                'message' => $data[5],
                'details' => isset($data[6]) ? json_decode($data[6]) : null,
            ];
        }

        return $this->parseAppErrorLine($line);
    }

    /**
     * Parse an error line
     */
    protected function parseAppErrorLine(string $line)
    {
        if (preg_match($this->appLogErrorPattern, $line, $data)) {
            return [
                'date' => $data[1],
                'time' => Carbon::parse($data[2])
                    ->setTimezone(config('app.timezone'))
                    ->format('m/d h:mA'),
                'env' => $data[3],
                'level' => $data[4],
                'message' => $data[5],
                'details' => [],
            ];
        }

        return false;
    }
}

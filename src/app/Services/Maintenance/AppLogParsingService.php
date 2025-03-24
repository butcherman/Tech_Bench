<?php

namespace App\Services\Maintenance;

use Carbon\Carbon;

class AppLogParsingService extends LogUtilitiesService
{
    /**
     * Regex pattern for standard application log entries
     *
     * @var string
     */
    protected $appLogEntryPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*?)? (?:\{(.*?)\})? (?:\{(.*?)\})$/i';

    /**
     * Regex pattern for log entries that are missing Context or additional data
     *
     * @var string
     */
    protected $contextMissingPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*)?$/i';

    /**
     * Get log file stats and parse the log for viewing individual entries
     */
    public function getLogFileData(string $channel, string $logFile): array
    {
        $filePath = $this->validateLogFile($channel, $logFile);
        $logFileArray = $this->getLogFileArray($filePath);

        return $this->parseFileArray($logFileArray);
    }

    /**
     * Cycle through each line of file array and parse it to get entry level,
     * timestamp, entry, context, and stack trace if necessary.
     */
    protected function parseFileArray(array $logFileArray): array
    {
        $logEntries = [];

        // Cycle through log file array
        foreach ($logFileArray as $logEntry) {
            $parsed = $this->parseAppLine($logEntry);
            // If parsed fails, then we are dealing with a Stack Trace
            if (! $parsed) {
                $logEntries[array_key_last($logEntries)]['stack_trace'][] = $logEntry;
            } else {
                $logEntries[] = $parsed;
            }
        }

        return $logEntries;
    }

    /**
     * Parse an individual log line
     */
    protected function parseAppLine(string $line): array|false
    {
        // If this is a standard entry line, we will return normal data
        if (preg_match($this->appLogEntryPattern, $line, $data)) {

            return [
                'time' => Carbon::parse($data[1] . ' ' . $data[2])
                    ->setTimezone(config('app.timezone'))
                    ->format('m-d h:i A'),
                'env' => $data[3],
                'level' => $data[4],
                'message' => $data[5],
                'data' => $data[6] ? json_decode('{' . $data[6] . '}') : null,
                'context' => isset($data[7]) ? $data[7] : null,
            ];
        }

        // If the entry is missing context data, return normal data as well
        if (preg_match($this->contextMissingPattern, $line, $data)) {
            return [
                'time' => Carbon::parse($data[1] . ' ' . $data[2])
                    ->setTimezone(config('app.timezone'))
                    ->format('m-d h:i A'),
                'env' => $data[3],
                'level' => $data[4],
                'message' => $data[5],
                'data' => null,
                'context' => null,
            ];
        }

        return false;
    }
}

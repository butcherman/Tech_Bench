<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

/**
 * Log Utilities Trait holds all reusable Log File variables and methods
 */
trait LogUtilitiesTrait
{
    /**
     * Regex data for log file
     */
    protected $logEntryPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*?)(\{.*?\})? .?$/i';

    protected $logErrorPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*)?$/i';

    protected $logLevelPattern = '/\.(.*?): /i';

    /**
     * Log Levels
     */
    protected $logLevels = [
        ['name' => 'Emergency', 'icon' => 'fas fa-ambulance',            'color' => 'text-danger'],
        ['name' => 'Alert',     'icon' => 'fas fa-bullhorn',             'color' => 'text-danger'],
        ['name' => 'Critical',  'icon' => 'fas fa-heartbeat',            'color' => 'text-danger'],
        ['name' => 'Error',     'icon' => 'fas fa-times-circle',         'color' => 'text-danger'],
        ['name' => 'Warning',   'icon' => 'fas fa-exclamation-triangle', 'color' => 'text-warning'],
        ['name' => 'Notice',    'icon' => 'fas fa-exclamation-circle',   'color' => 'text-info'],
        ['name' => 'Info',      'icon' => 'fas fa-info',                 'color' => 'text-info'],
        ['name' => 'Debug',     'icon' => 'fas fa-bug',                  'color' => 'text-primary'],
    ];

    /**
     * Log Channels
     */
    protected $logChannels = [
        ['name' => 'Emergency',      'folder' => 'Emergency',   'channel' => 'emergency'],
        ['name' => 'Application',    'folder' => 'Application', 'channel' => 'daily'],
        ['name' => 'User',           'folder' => 'Users',       'channel' => 'user'],
        ['name' => 'Authentication', 'folder' => 'Auth',        'channel' => 'auth'],
        ['name' => 'Customer',       'folder' => 'Cust',        'channel' => 'cust'],
        ['name' => 'Tech Tip',       'folder' => 'TechTip',     'channel' => 'tip'],
    ];

    /**
     * Constructor will reformat local variables as Collections
     */
    public function __construct()
    {
        $this->logLevels = collect($this->logLevels);
        $this->logChannels = collect($this->logChannels);
    }

    /**
     * Parse log data and find out channels that have files in them.
     * Channels that do not have any active log files will not be displayed
     */
    protected function getLogChannels(bool $all = false)
    {
        if ($all) {
            return $this->logChannels; // ->only(['name', 'channel']);
        }

        $availableChannels = [];

        foreach ($this->logChannels as $channel) {
            if (Storage::disk('logs')->files($channel['folder'])) {
                $availableChannels[] = $channel['name'];
            }
        }

        return $availableChannels;
    }

    /**
     * Return the list of available log levels
     */
    protected function getLogLevels()
    {
        return $this->logLevels;
    }

    /**
     * Return Channel data based on name
     */
    protected function getChannel(string $channelName)
    {
        return $this->logChannels->where('name', $channelName)->first();
    }

    /**
     * Return a list of file names for the selected channel
     */
    protected function getLogList(string $channel)
    {
        $channelData = $this->getChannel($channel);
        //  Because users are stupid, we have to validate the channel exists
        if ($channelData === null) {
            return null;
        }

        $fileList = Storage::disk('logs')->files($channelData['folder']);

        //  Get the stats for each log file
        $statList = [];
        foreach ($fileList as $file) {
            $pathInfo = pathinfo($file);
            if ($pathInfo['extension'] === 'log') {
                $stats = $this->getFileStats($file);
                $stats['filename'] = $pathInfo['filename'];
                $statList[] = $stats;
            }
        }

        return array_reverse($statList);
    }

    /**
     * Get the Log Level Stats for a log file
     */
    protected function getFileStats(string|array $file)
    {
        $stats = $this->getCleanStats();
        if (! is_array($file)) {
            $file = $this->getFileToArray($file);
        }

        //  Cycle through each line of the file
        foreach ($file as $line) {
            $level = $this->getLineLevel($line);
            if ($level) {
                //  We only increment the level data if it is a valid log line and not part of a multiline stack trace
                if (isset($stats[strtolower($level)])) {
                    $stats[strtolower($level)]++;
                    $stats['total']++;
                }
            }
        }

        return $stats;
    }

    /**
     * Parse the file to identify each individual log entry
     */
    protected function parseLogFile(array $logFile)
    {
        $parsed = [];
        $errorKey = null;

        //  Go through each line of the file
        foreach ($logFile as $line) {
            $parse = $this->parseLine($line);
            if (! $parse) {
                $parse = $this->parseErrorLine($line);
                if ($parse) {
                    $parsed[] = $parse;
                    $errorKey = array_key_last($parsed);
                } else {
                    $parsed[$errorKey]['stack_trace'][] = $line;
                }
            } else {
                $parsed[] = $parse;
            }
        }

        return $parsed;
    }

    /**
     * Use Regex to parse the portions of each log line
     */
    protected function parseLine($line)
    {
        //  If it is a standard log line, we return standard data
        if (preg_match($this->logEntryPattern, $line, $data)) {
            return [
                'date' => $data[1],
                'time' => $data[2],
                'env' => $data[3],
                'level' => $data[4],
                'message' => $data[5],
                'details' => isset($data[6]) ? json_decode($data[6]) : null,
            ];
        }

        //  If the line is a major error, the line and stack trace will follow
        return false;
    }

    /**
     * Use Regex to get the error line
     */
    protected function parseErrorLine($line)
    {
        if (preg_match($this->logErrorPattern, $line, $data)) {
            return [
                'date' => $data[1],
                'time' => $data[2],
                'env' => $data[3],
                'level' => $data[4],
                'message' => $data[5],
                'details' => [],
            ];
        }

        //  If the line is a major error, the line and stack trace will follow
        return false;
    }

    /**
     * Take a log file and convert each line into an array item
     */
    protected function getFileToArray(string $file)
    {
        return file(Storage::disk('logs')->path($file));
    }

    /**
     * Return a clean list of stats for a log file
     */
    protected function getCleanStats()
    {
        $statList = [];
        foreach ($this->logLevels as $level) {
            $statList[strtolower($level['name'])] = 0;
        }

        $statList['total'] = 0;

        return $statList;
    }

    /**
     * Get the log level of a line
     */
    protected function getLineLevel(string $line)
    {
        if (preg_match($this->logLevelPattern, $line, $data)) {
            return $data[1];
        }

        return false;
    }

    /**
     * Validate a log file and channel exist
     */
    protected function validateLogFile(string $channel, string $file)
    {
        $channel = $this->getChannel($channel);
        if (! $channel) {
            return false;
        }

        if (! Storage::disk('logs')->exists($channel['folder'].DIRECTORY_SEPARATOR.$file.'.log')) {
            return false;
        }

        return true;
    }
}

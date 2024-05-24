<?php

namespace App\Traits;

use App\Exceptions\Maintenance\BadLogChannelException;
use App\Exceptions\Maintenance\LogFileMissingException;
use Illuminate\Support\Facades\Storage;

trait LogUtilitiesTrait
{
    /**
     * Regex data for log file
     */
    protected $logEntryPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*?)(\{.*?\})? .?$/i';

    protected $logErrorPattern = '/^\[(\d{4}-\d{2}-\d{2}) (\d{2}:\d{2}:\d{2})\] (?:.*?(\w+)\.)(?:.*?(\w+)\:) (.*)?$/i';

    protected $logLevelPattern = '/\.(.*?): /i';


    protected $logLevels;
    protected $logChannels;
    protected $storage;

    /**
     * Constructor will reformat local variables as Collections
     */
    public function __construct()
    {
        /**
         * Build Log Levels
         */
        $this->logLevels = collect([
            ['name' => 'emergency', 'icon' => 'fas fa-ambulance', 'color' => 'text-danger'],
            ['name' => 'alert', 'icon' => 'fas fa-bullhorn', 'color' => 'text-danger'],
            ['name' => 'critical', 'icon' => 'fas fa-heartbeat', 'color' => 'text-danger'],
            ['name' => 'error', 'icon' => 'fas fa-times-circle', 'color' => 'text-danger'],
            ['name' => 'warning', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'text-warning'],
            ['name' => 'notice', 'icon' => 'fas fa-exclamation-circle', 'color' => 'text-info'],
            ['name' => 'info', 'icon' => 'fas fa-info', 'color' => 'text-info'],
            ['name' => 'debug', 'icon' => 'fas fa-bug', 'color' => 'text-primary'],
        ]);

        /**
         * Build Log Channels
         */
        $this->logChannels = collect([
            ['name' => 'Application', 'folder' => 'Application', 'channel' => 'daily'],
            ['name' => 'Authentication', 'folder' => 'Auth', 'channel' => 'auth'],
            ['name' => 'User', 'folder' => 'Users', 'channel' => 'user'],
            ['name' => 'Customer', 'folder' => 'Cust', 'channel' => 'cust'],
            ['name' => 'Tech Tip', 'folder' => 'TechTip', 'channel' => 'tip'],
        ]);

        /**
         * Storage location for the logs
         */
        $this->storage = Storage::disk('logs');
    }

    /**
     * Return list of log files along with stats of each file
     */
    protected function getChannelLogs(string $channelName)
    {
        $logFiles = $this->getLogList($channelName);

        if (is_null($logFiles)) {
            return null;
        }

        $statArray = [];
        foreach ($logFiles as $log) {
            $pathInfo = pathinfo($log);
            if ($pathInfo['extension'] === 'log') {
                $statData = $this->getFileStats($log);
                $statData['filename'] = $pathInfo['filename'];
                $statArray[] = $statData;
            }
        }

        // Reverse the array to put the newest files at the top
        return array_reverse($statArray);
    }

    /**
     * Validate a channel, or throw exception
     */
    protected function validateChannel(string $channelName)
    {
        if (!$this->getChannel($channelName)) {
            throw new BadLogChannelException($channelName);
        }
    }

    /**
     * Return the Channel Object based on the channel name
     */
    protected function getChannel(string $channelName)
    {
        return $this->logChannels->where('channel', $channelName)->first();
    }

    /**
     * Get the list of log files for the selected channel
     */
    protected function getLogList(string $channel)
    {
        $channelData = $this->getChannel($channel);

        // Because users suck, we have to validate the channel 
        if ($channelData === null) {
            return null;
        }

        // Get the list of Log Files for the selected channel 
        $fileList = $this->storage->files($channelData['folder']);

        return $fileList;
    }

    /**
     * Return a specific log file
     */
    protected function getLogFile(string $channel, string $fileName)
    {
        $channel = $this->getChannel($channel);
        $logFile = $channel['folder'] . DIRECTORY_SEPARATOR . $fileName . '.log';

        if ($this->storage->missing($logFile)) {
            throw new LogFileMissingException($logFile);
        }

        return $logFile;
    }

    /**
     * Take log file and convert each line to an array entry
     */
    protected function getFileToArray(string $fileName)
    {
        return file($this->storage->path($fileName));
    }

    /**
     * Parse a log file to identify each individual log entry
     */
    protected function parseLogFile(array $logFile)
    {
        $parsed = [];
        $errorKey = null;

        //  Go through each line of the file
        foreach ($logFile as $line) {
            $parse = $this->parseLine($line);
            if (!$parse) {
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
     * Get the Log Level Stats for a log file
     */
    protected function getFileStats(string|array $logFile)
    {
        $stats = $this->getCleanStats();

        // If file was not parsed to array, do it now
        if (!is_array($logFile)) {
            $logFile = $this->getFileToArray($logFile);
        }

        //  Cycle through each line of the file to get log level type
        foreach ($logFile as $line) {
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
     * Return a clean zeroed out list of stats for a log file
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
}
<?php

namespace App\Traits;

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
            ['name' => 'Auth', 'folder' => 'Auth', 'channel' => 'auth'],
            ['name' => 'User', 'folder' => 'Users', 'channel' => 'user'],
            ['name' => 'Authentication', 'folder' => 'Auth', 'channel' => 'auth'],
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
     * Take log file and convert each line to an array entry
     */
    protected function getFileToArray(string $fileName)
    {
        return file($this->storage->path($fileName));
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
<?php

namespace App\Service\Maint;

use App\Exceptions\Maintenance\BadLogChannelException;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class LogUtilitiesService
{
    /**
     * Regex pattern to get the level of the log entry
     */
    protected $appLogLevelPattern = '/\.(.*?): /i';

    /**
     * All possible Log Levels
     */
    protected $appLogLevels;

    /**
     * Storage location for log files
     */
    protected $storage;

    /**
     * All Available Log Channels (folders in the log storage directory)
     */
    protected $logChannels;

    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        // Build default data
        $this->storage = Storage::disk('logs');
        $this->appLogLevels = collect([
            ['name' => 'emergency', 'icon' => 'fas fa-ambulance', 'color' => 'text-danger'],
            ['name' => 'alert', 'icon' => 'fas fa-bullhorn', 'color' => 'text-danger'],
            ['name' => 'critical', 'icon' => 'fas fa-heartbeat', 'color' => 'text-danger'],
            ['name' => 'error', 'icon' => 'fas fa-times-circle', 'color' => 'text-danger'],
            ['name' => 'warning', 'icon' => 'fas fa-exclamation-triangle', 'color' => 'text-warning'],
            ['name' => 'notice', 'icon' => 'fas fa-exclamation-circle', 'color' => 'text-info'],
            ['name' => 'info', 'icon' => 'fas fa-info', 'color' => 'text-info'],
            ['name' => 'debug', 'icon' => 'fas fa-bug', 'color' => 'text-primary'],
        ]);
        $this->logChannels = $this->storage->directories();
        sort($this->logChannels);
    }

    /**
     * Return all available log channels
     */
    public function getLogChannels()
    {
        return $this->logChannels;
    }

    /**
     * Return all available log levels
     */
    public function getLogLevels()
    {
        return $this->appLogLevels;
    }

    /**
     * Validate that a given channel name is an actual channel
     */
    public function validateLogChannel(string $channelName)
    {
        if (! in_array($channelName, $this->logChannels)) {
            throw new BadLogChannelException($channelName);
        }

        return true;
    }

    /**
     * Get a list of Log Files for the selected channel
     */
    public function getLogList(string $channelName)
    {
        return match ($this->getChannelType($channelName)) {
            'app' => $this->getAppLogs($channelName),
            'nginx' => $this->getNginxLogs(),
        };
    }

    /**
     * Determine the type of log file (for parsing data later)
     */
    public function getChannelType(string $channelName)
    {
        Log::debug('Checking log file type for channel - '.$channelName);
        $specialList = ['nginx'];

        if (in_array(strtolower($channelName), $specialList)) {
            Log::debug('Found log channel type - '.$channelName);

            return strtolower($channelName);
        }

        Log::debug('Found log channel type - app');

        return 'app';
    }

    /***************************************************************************
     *                  Internal Methods
     ***************************************************************************/

    /**
     * Get a list of files in the channel directory
     */
    protected function getLogFileList(string $channelName)
    {
        return $this->storage->files($channelName);
    }

    /**
     * Parse a file so that each line is an array value
     */
    protected function getFileArray(string $fileName)
    {
        return file($this->storage->path($fileName));
    }

    /***************************************************************************
     * Application Log Files
     ***************************************************************************/

    /**
     * Get a list of application logs with statistic data included
     */
    protected function getAppLogs(string $channelName)
    {
        Log::debug('Building list of log files and statistics for channel '.$channelName);
        $logList = $this->getLogFileList($channelName);
        $logStatList = [];

        foreach ($logList as $log) {
            $pathInfo = pathinfo($log);

            // We only parse log files all others will be ignored
            if ($pathInfo['extension'] === 'log') {
                Log::debug('Getting File Stats for Log File '.$log);

                $fileArray = $this->getFileArray($log);
                $statData = $this->getAppLogStats($fileArray);
                $statData['filename'] = $pathInfo['filename'];
                $logStatList[] = $statData;
            }
        }

        return $logStatList;

    }

    /**
     * Build the list of each entry type for the selected log file
     */
    protected function getAppLogStats(array $logData)
    {
        $stats = $this->getCleanAppStats();

        // Cycle through each line of the log file and get the log level type
        foreach ($logData as $line) {
            $stats = $this->buildAppLogLineStats($line, $stats);
        }

        return $stats;
    }

    /**
     * Increase the stats level for the specific log line
     */
    protected function buildAppLogLineStats(string $line, array $currentStats)
    {
        $lineLevel = $this->getAppLineLevel($line);
        if ($lineLevel) {
            $currentStats[strtolower($lineLevel)]++;
            $currentStats['total']++;
        }

        return $currentStats;
    }

    /**
     * Determine the level of the log entry
     */
    protected function getAppLineLevel(string $line)
    {
        if (preg_match($this->appLogLevelPattern, $line, $data)) {
            $level = strtolower($data[1]);

            // The level must be a valid line level
            if ($this->appLogLevels->contains('name', $level)) {
                return $level;
            }
        }

        return false;
    }

    /**
     * Build an array with all log types, and counts of zero
     */
    protected function getCleanAppStats()
    {
        $statList = [];
        foreach ($this->appLogLevels as $level) {
            $statList[strtolower($level['name'])] = 0;
        }

        $statList['total'] = 0;

        return $statList;
    }

    /***************************************************************************
     * NGINX Log Files
     ***************************************************************************/

    /**
     * Get a list of NGINX Logs with entry statistic data included
     */
    protected function getNginxLogs()
    {
        Log::debug('Building list of log files and statistics for channel - NGINX');
        $logList = $this->getLogFileList('NGINX');
        $logStatList = [];

        foreach ($logList as $log) {
            $pathInfo = pathinfo($log);

            // We only parse log files, all others will be ignored
            Log::debug('Getting File Stats for Log File '.$log);

            $fileArray = $this->getFIleArray($log);
            $logStatList[] = [
                'filename' => $pathInfo['filename'],
                'total' => count($fileArray),
            ];
        }

        return $logStatList;
    }
}

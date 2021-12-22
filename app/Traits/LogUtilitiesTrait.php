<?php

namespace App\Traits;

use Illuminate\Support\Arr;
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
        ['name' => 'Emergency', 'icon' => 'fas fa-ambulance',            'color' => ''],
        ['name' => 'Alert',     'icon' => 'fas fa-bullhorn',             'color' => ''],
        ['name' => 'Critical',  'icon' => 'fas fa-heartbeat',            'color' => ''],
        ['name' => 'Error',     'icon' => 'fas fa-times-circle',         'color' => ''],
        ['name' => 'Warning',   'icon' => 'fas fa-exclamation-triangle', 'color' => ''],
        ['name' => 'Notice',    'icon' => 'fas fa-exclamation-circle',   'color' => ''],
        ['name' => 'Info',      'icon' => 'fas fa-info',                 'color' => ''],
        ['name' => 'Debug',     'icon' => 'fas fa-bug',                  'color' => ''],
    ];

    /**
     * Log Channels
     */
    protected $logChannels = [
        ['name' => 'Emergency',      'folder' => 'Emergency'],
        ['name' => 'Application',    'folder' => 'Application'],
        ['name' => 'User',           'folder' => 'Users'],
        ['name' => 'Authentication', 'folder' => 'Auth'],
        ['name' => 'Customer',       'folder' => 'Cust'],
        ['name' => 'Tech Tip',       'folder' => 'TechTip'],
    ];

    /**
     * Get the full details of a channel based on the name
     */
    protected function getChannelDetails($channel)
    {
        return Arr::first($this->logChannels, function($value) use ($channel)
        {
            return $value['name'] == $channel;
        });
    }

    /**
     * Get all of the files for a specific channel
     */
    protected function getFileList($channel)
    {
        $list   = Storage::disk('logs')->files($channel['folder']);
        $sorted = [];

        foreach($list as $file)
        {
            $pathInfo = pathinfo($file);
            if($pathInfo['extension'] === 'log')
            {
                $sorted[] = $pathInfo['filename'];
            }
        }

        return $sorted;
    }

    /**
     * List the files for a specific channel, and get the log level stats for all files combined
     */
    protected function getChannelStats($channel)
    {
        $channel  = $this->getChannelDetails($channel);
        $fileList = $this->getFileList($channel);
        $statList = [];

        //  Cycle through all log files
        foreach($fileList as $file)
        {
            $fileArr    = $this->getFileToArray($file, $channel);
            $statList[] = $this->getFileStats($fileArr, $file);
        }

        return $statList;
    }

    /**
     * Get the stats for a specific file
     */
    protected function getFileStats($fileArr, $filename)
    {
        $stats   = $this->resetStats($filename);

        //  Cycle through each line of the file
        foreach($fileArr as $line)
        {
            $level = $this->getLineLevel($line);
            if($level)
            {
                //  We only increment the level data if it is a valid log line and not part of a multiline stack trace
                if(isset($stats[strtolower($level)]))
                {
                    $stats[strtolower($level)]++;
                    $stats['total']++;
                }
            }
        }

        return $stats;
    }

    /**
     * Provide a clean array of stats
     */
    protected function resetStats($filename)
    {
        $statList = [];
        foreach($this->logLevels as $level)
        {
            $statList[strtolower($level['name'])] = 0;
        }

        $statList['filename'] = $filename;
        $statList['total']    = 0;

        return $statList;
    }

    /**
     * Take a log file and convert each line into an array item
     */
    protected function getFileToArray($file, $channel)
    {
        return file(Storage::disk('logs')->path($channel['folder'].DIRECTORY_SEPARATOR.$file.'.log'));
    }

    /**
     * Get the log level of a line
     */
    protected function getLineLevel($line)
    {
        if(preg_match($this->logLevelPattern, $line, $data))
        {
            return $data[1];
        }

        return false;
    }

    /**
     * Parse the log file to identify each section of the message
     */
    protected function parseFile($fileArr)
    {
        $parsed   = [];
        $errorKey = null;

        //  Go through each line of the file
        foreach($fileArr as $line)
        {
            $parse = $this->parseLine($line);
            if(!$parse)
            {
                $parse = $this->parseErrorLine($line);
                if($parse)
                {
                    $parsed[] = $parse;
                    $errorKey = array_key_last($parsed);
                }
                else
                {
                    $parsed[$errorKey]['stack_trace'][] = $line;
                }
            }
            else
            {
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
        if(preg_match($this->logEntryPattern, $line, $data))
        {
            return [
                'date'    => $data[1],
                'time'    => $data[2],
                'env'     => $data[3],
                'level'   => $data[4],
                'message' => $data[5],
                'details' => isset($data[6]) ? [json_decode($data[6])] : null,
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
        if(preg_match($this->logErrorPattern, $line, $data))
        {
            return [
                'date'    => $data[1],
                'time'    => $data[2],
                'env'     => $data[3],
                'level'   => $data[4],
                'message' => $data[5],
                'details' => [],
            ];
        }

        //  If the line is a major error, the line and stack trace will follow
        return false;
    }
}

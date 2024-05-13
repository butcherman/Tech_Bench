<?php

namespace App\Traits;

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

    /**
     * Constructor will reformat local variables as Collections
     */
    public function __construct()
    {
        /**
         * Log Levels
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
        ])->map(fn($row) => (object) $row);

        /**
         * Log Channels
         */
        $this->logChannels = collect([
            ['name' => 'Application', 'folder' => 'Application', 'channel' => 'daily'],
            ['name' => 'Auth', 'folder' => 'Auth', 'channel' => 'auth'],
            ['name' => 'User', 'folder' => 'Users', 'channel' => 'user'],
            ['name' => 'Authentication', 'folder' => 'Auth', 'channel' => 'auth'],
            ['name' => 'Customer', 'folder' => 'Cust', 'channel' => 'cust'],
            ['name' => 'Tech Tip', 'folder' => 'TechTip', 'channel' => 'tip'],
        ])->map(fn($row) => (object) $row);
    }
}
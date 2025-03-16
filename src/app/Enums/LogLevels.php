<?php

namespace App\Enums;

/*
|---------------------------------------------------------------------------
| Log Levels Enum is a list of all possible logging levels for this app
|---------------------------------------------------------------------------
*/

enum LogLevels
{
    case emergency;
    case alert;
    case critical;
    case error;
    case warning;
    case notice;
    case info;
    case debug;

    public function getLevelIcon(): string
    {
        return match ($this) {
            LogLevels::emergency => 'fa-ambulance',
            LogLevels::alert => 'fa-bullhorn',
            LogLevels::critical => 'fa-heartbeat',
            LogLevels::error => 'times-circle',
            LogLevels::warning => 'exclamation-triangle',
            LogLevels::notice => 'fa-exclamation-circle',
            LogLevels::info => 'fa-info',
            LogLevels::debug => 'fa-bug',
        };
    }

    public function getLevelTextColor(): string
    {
        return match ($this) {
            LogLevels::emergency => 'text-danger',
            LogLevels::alert => 'text-danger',
            LogLevels::critical => 'text-danger',
            LogLevels::error => 'text-error',
            LogLevels::warning => 'text-warning',
            LogLevels::notice => 'text-warning',
            LogLevels::info => 'text-primary',
            LogLevels::debug => 'text-info',
        };
    }
}

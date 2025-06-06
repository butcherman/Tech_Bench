<?php

namespace App\Enums;

/*
|-------------------------------------------------------------------------------
| Log Channels are the folders that hold specific log files
|-------------------------------------------------------------------------------
*/

enum LogChannels: string
{
    case application = 'application';
    case authentication = 'authentication';

    public function getFolder()
    {
        return match ($this) {
            LogChannels::application => 'Application',
            LogChannels::authentication => 'Auth',
        };
    }
}

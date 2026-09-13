<?php

namespace App\Enums;

enum BackupType: string
{
    case Scheduled = 'scheduled';
    case Manual = 'manual';
    case Cli = 'cli';
    case Unknown = 'unknown';
}

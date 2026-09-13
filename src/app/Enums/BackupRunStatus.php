<?php

namespace App\Enums;

enum BackupRunStatus: string
{
    case Running = 'running';
    case Completed = 'completed';
    case Failed = 'failed';
}
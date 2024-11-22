<?php

namespace App\Enums;

/*
|---------------------------------------------------------------------------
| DiskEnum is a list of possible File Storage Disks configured in
| config.filesystems.disks
|---------------------------------------------------------------------------
*/
enum DiskEnum: string
{
    case local = 'local';
    case customers = 'customers';
    case tips = 'tips';
    case public = 'public';
    case logs = 'logs';
    case backups = 'backups';
    case security = 'security';
}

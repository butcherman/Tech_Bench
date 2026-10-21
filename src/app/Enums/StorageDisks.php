<?php

namespace App\Enums;

/*
|---------------------------------------------------------------------------
| Disk Enum is a list of possible File Storage Disks configured in
| config.filesystems.disks
|---------------------------------------------------------------------------
*/

enum StorageDisks: string
{
    case local = 'local';
    case customers = 'customers';
    case tips = 'tips';
    case public = 'public';
    case logs = 'logs';
    case backups = 'backups';
    case security = 'security';
    case links = 'fileLinks';
}

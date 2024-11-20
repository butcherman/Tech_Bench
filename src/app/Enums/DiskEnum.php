<?php

namespace App\Enums;

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

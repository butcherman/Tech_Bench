<?php

namespace App\Enums;

/*
|-------------------------------------------------------------------------------
| ContainerList is a list of all Tech Bench Docker Containers.
|-------------------------------------------------------------------------------
*/

enum ContainerList: string
{
    case App = 'app';
    case Nginx = 'nginx';
    case MySql = 'mysql';
    case Reverb = 'reverb';
    case Redis = 'redis';
    case Meilisearch = 'meilisearch';
    case Queue = 'queue';
    case Scheduler = 'scheduler';
}
